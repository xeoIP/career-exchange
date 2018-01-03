<?php

namespace App\Http\Controllers;

use App\Helpers\Arr;
use App\Http\Requests\ContactRequest;
use App\Models\Page;
use App\Mail\FormSent;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Torann\LaravelMetaTags\Facades\MetaTag;

class PageController extends FrontController
{
    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($slug)
    {
        $page = Page::where('slug', $slug)->trans()->first();
        if (empty($page)) {
            abort(404);
        }
        view()->share('page', $page);
        view()->share('uriPathPageSlug', $slug);

        // SEO
        $title = $page->title;
        $description = str_limit(str_strip($page->content), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        if (!empty($page->picture)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            $this->og->image(Storage::url($page->picture), [
                'width'  => 600,
                'height' => 600,
            ]);
        }
        view()->share('og', $this->og);
        view()->share('pageName', $this->page);
        return view('pages.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        // SEO
        $title = t('Contact Us');
        $description = str_limit(str_strip(t('Contact Us')), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        view()->share('og', $this->og);

        return view('pages.contact');
    }

    /**
     * @param ContactRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function contactPost(ContactRequest $request)
    {
        // Store Contact Info
		$contactForm = [
            'country_code' => $this->country->get('code'),
            'country'      => $this->country->get('name'),
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'company_name' => $request->input('company_name'),
            'email'        => $request->input('email'),
            'message'      => $request->input('message'),
        ];
		$contactForm = Arr::toObject($contactForm);

        // Send Contact Email
        try {
            if (config('settings.app_email')) {
                $recipient = [
                    'email' => config('settings.app_email'),
                    'name'  => config('settings.app_name'),
                ];
                $recipient = Arr::toObject($recipient);
                Mail::send(new FormSent($contactForm, $recipient));
            } else {
                $admins = User::where('is_admin', 1)->get();
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        Mail::send(new FormSent($contactForm, $admin));
                    }
                }
            }
			flash(t("Your message has been sent to our moderators. Thank you"))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect(config('app.locale') . '/' . trans('routes.contact'));
    }
}
