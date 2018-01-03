<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Arr;
use App\Models\Post;

class ReportSent extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $report;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     * @param $report
     * @param $recipient
     */
    public function __construct(Post $post, $report, $recipient)
    {
        $this->post = $post;
        $this->report = (is_array($report)) ? Arr::toObject($report) : $report;

		$this->to($recipient->email, $recipient->name);
		$this->replyTo($this->report->email, $this->report->email);
        $this->subject(trans('mail.post_report_sent_title', [
            'app_name'      => config('settings.app_name'),
            'country_code'  => $post->country_code
        ]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.report-sent');
    }
}
