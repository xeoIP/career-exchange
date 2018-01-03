<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    /**
     * PostNotification constructor.
     * @param $post
     * @param $adminUser
     */
    public function __construct($post, $adminUser)
    {
        $this->post = $post;

        $this->to($adminUser->email, $adminUser->name);
        $this->subject(trans('mail.post_notification_title'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.notification');
    }
}
