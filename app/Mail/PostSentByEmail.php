<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Arr;
use App\Models\Post;

class PostSentByEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     * @param $mailData
     */
    public function __construct(Post $post, $mailData)
    {
        $this->post = $post;
        $this->mailData = (is_array($mailData)) ? Arr::toObject($mailData) : $mailData;

        $this->to($this->mailData->recipient_email, $this->mailData->recipient_email);
		$this->replyTo($this->mailData->sender_email, $this->mailData->sender_email);
        $this->subject(trans('mail.post_sent_by_email_title', [
            'app_name' => config('settings.app_name'),
            'country_code' => $post->country_code
        ]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.sent-by-email');
    }
}
