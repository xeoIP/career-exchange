<?php

namespace App\Http\Controllers\Post;

use App\Helpers\Arr;
use App\Http\Requests\ReportRequest;
use App\Models\Post;
use App\Models\ReportType;
use App\Http\Controllers\FrontController;
use App\Models\User;
use App\Mail\ReportSent;
use Illuminate\Support\Facades\Mail;
use Torann\LaravelMetaTags\Facades\MetaTag;

class ReportController extends FrontController
{
    /**
     * ReportController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->commonQueries();
            
            return $next($request);
        });
    }
    
    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // Get Report abuse types
        $report_types = ReportType::trans()->get();
        view()->share('report_types', $report_types);
    }
    
    public function showReportForm($postId)
    {
        $data = [];
        
        // Get Post
        $data['post'] = Post::find($postId);
        if (empty($data['post'])) {
            abort(404);
        }
        
        // Meta Tags
        $data['title'] = t('Report for :title', ['title' => ucfirst($data['post']->title)]);
        $description = t('Send a report for :title', ['title' => ucfirst($data['post']->title)]);
        
        MetaTag::set('title', $data['title']);
        MetaTag::set('description', strip_tags($description));
        
        // Open Graph
        $this->og->title($data['title'])->description($description);
        view()->share('og', $this->og);
        
        return view('post.report', $data);
    }
    
    /**
     * @param $postId
     * @param ReportRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendReport($postId, ReportRequest $request)
    {
        // Get Post
        $post = Post::find($postId);
        if (empty($post)) {
            abort(404);
        }
        
        // Store Report
        $report = [
            'post_id'        => $postId,
            'report_type_id' => $request->input('report_type'),
            'email'          => $request->input('email'),
            'message'        => $request->input('message'),
        ];
        
        // Send Abuse Report to admin
        try {
            if (config('settings.app_email')) {
                $recipient = [
                    'email' => config('settings.app_email'),
                    'name'  => config('settings.app_name'),
                ];
                $recipient = Arr::toObject($recipient);
                Mail::send(new ReportSent($post, $report, $recipient));
            } else {
                $admins = User::where('is_admin', 1)->get();
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        Mail::send(new ReportSent($post, $report, $admin));
                    }
                }
            }
            
            flash(t('Your report has send successfully to us. Thank you!'))->success();
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            
            return back()->withInput();
        }
        
        return redirect(config('app.locale') . '/' . slugify($post->title) . '/' . $post->id . '.html');
    }
    
}
