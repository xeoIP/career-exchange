<?php

namespace App\Http\Controllers\Post;

use App\Helpers\Rules;
use App\Http\Requests\PackageRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Session;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use App\Http\Controllers\Post\Traits\PaymentTrait;

class PackageController extends FrontController
{
    use PaymentTrait;

    public $request;
    public $data;
    public $msg = [];
    public $uri = [];
    public $packages;
    public $paymentMethods;

    /**
     * PackageController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->request = $request;
            $this->commonQueries();

            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // Messages
        if (getSegment(2) == 'create') {
            $this->msg['post']['success'] = t("Your ad has been created.");
        } else {
            $this->msg['post']['success'] = t("Your ad has been updated.");
        }
        $this->msg['checkout']['success'] = t("We have received your payment.");
        $this->msg['checkout']['cancel'] = t("We have not received your payment. Payment cancelled.");
        $this->msg['checkout']['error'] = t("We have not received your payment. An error occurred.");
	
		// Set URLs
        if (getSegment(2) == 'create') {
            $this->uri['previousUrl'] = config('app.locale') . '/posts/create/#entryToken/packages';
            $this->uri['nextUrl'] = config('app.locale') . '/posts/create/#entryToken/finish';
			$this->uri['paymentCancelUrl'] = url(config('app.locale') . '/posts/create/#entryToken/payment/cancel');
			$this->uri['paymentReturnUrl'] = url(config('app.locale') . '/posts/create/#entryToken/payment/success');
        } else {
            $this->uri['previousUrl'] = config('app.locale') . '/posts/#entryId/packages';
            $this->uri['nextUrl'] = config('app.locale') . '/#title/#entryId.html';
			$this->uri['paymentCancelUrl'] = url(config('app.locale') . '/posts/#entryId/payment/cancel');
			$this->uri['paymentReturnUrl'] = url(config('app.locale') . '/posts/#entryId/payment/success');
        }

        // Payment Helper init.
        PaymentHelper::$country = $this->country;
        PaymentHelper::$lang = $this->lang;
        PaymentHelper::$msg = $this->msg;
        PaymentHelper::$uri = $this->uri;
    
        // Get Packages
        $this->packages = Package::trans()->with('currency')->orderBy('lft')->get();
        view()->share('packages', $this->packages);
        view()->share('countPackages', $this->packages->count());
    
        // Get Payment Methods
        $this->paymentMethods = PaymentMethod::where(function ($query) {
            $query->whereRaw('FIND_IN_SET("' . $this->country->get('icode') . '", LOWER(countries)) > 0')
                ->orWhereNull('countries');
        })->orderBy('lft')->get();
        view()->share('paymentMethods', $this->paymentMethods);
        view()->share('countPaymentMethods', $this->paymentMethods->count());

        // Keep the Post's creation message
        // session()->keep(['message']);
        if (getSegment(2) == 'create') {
            if (session()->has('tmpPostId')) {
                session()->flash('message', t('Your ad has been created.'));
            }
        }
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm($postIdOrToken)
    {
        $data = [];

        // Get Post
        if (getSegment(2) == 'create') {
            if (!Session::has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $postIdOrToken)->first();
        } else {
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('user_id', $this->user->id)->where('id', $postIdOrToken)->first();
        }

        if (empty($post)) {
            abort(404);
        }
        view()->share('post', $post);

        // Get parent Category (for wizard nav.)
        $pcat = Category::transById($post->category_id);
        if (!empty($pcat)) {
            if ($pcat->parent_id != 0) {
                $pcat = Category::find($pcat->parent_id);
            }
        }
        view()->share('pcat', $pcat);

        // Get current Payment
        $currentPayment = null;
        if ($post->featured == 1) {
            $currentPayment = Payment::where('post_id', $post->id)->orderBy('created_at', 'DESC')->first();
        }
        view()->share('currentPayment', $currentPayment);

        // Get the package of the current payment (if exists)
        if (isset($currentPayment) && !empty($currentPayment)) {
            $currentPaymentPackage = Package::transById($currentPayment->package_id);
            view()->share('currentPaymentPackage', $currentPaymentPackage);
        }

        // Meta Tags
        if (getSegment(2) == 'create') {
            MetaTag::set('title', getMetaTag('title', 'create'));
            MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
            MetaTag::set('keywords', getMetaTag('keywords', 'create'));
        } else {
            MetaTag::set('title', t('Update My Ad'));
            MetaTag::set('description', t('Update My Ad'));
        }

        return view('post.packages', $data);
    }

    /**
     * Store a new ad post.
     *
     * @param $postIdOrToken
     * @param PackageRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForm($postIdOrToken, PackageRequest $request)
    {
        // Get Post
        if (getSegment(2) == 'create') {
            if (!Session::has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $postIdOrToken)->first();
        } else {
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('user_id', $this->user->id)->where('id', $postIdOrToken)->first();
        }

        if (empty($post)) {
            abort(404);
        }
    
        // MAKE A PAYMENT (IF NEEDED)
    
        // Check if the selected Package has been already paid for this Post
        $alreadyPaidPackage = false;
        $currentPayment = Payment::where('post_id', $post->id)->orderBy('created_at', 'DESC')->first();
        if (!empty($currentPayment)) {
            if ($currentPayment->package_id == $request->input('package')) {
                $alreadyPaidPackage = true;
            }
        }

        // Check if Payment is required
        $package = Package::find($request->input('package'));
        if (!empty($package) && $package->price > 0 && $request->filled('payment_method') && !$alreadyPaidPackage) {
            // Send the Payment
            return $this->sendPayment($request, $post);
        }
    
        // IF NO PAYMENT IS MADE (CONTINUE)
    
        // Get the next URL
        if (getSegment(2) == 'create') {
            $request->session()->flash('message', t('Your ad has been created.'));
            $nextStepUrl = config('app.locale') . '/posts/create/' . $postIdOrToken . '/finish';
        } else {
            flash(t("Your ad has been updated."))->success();
            $nextStepUrl = config('app.locale') . '/' . slugify($post->title) . '/' . $post->id . '.html';
        }
    
        // Redirect
        return redirect($nextStepUrl);
    }
}
