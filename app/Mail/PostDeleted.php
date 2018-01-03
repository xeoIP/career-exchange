<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    /**
     * Create a new message instance.
     *
     * @param $post
     */
    public function __construct($post)
    {
        $this->post = $post;

        $this->to($post->email, $post->contact_name);
        $this->subject(trans('mail.post_deleted_title', ['title' => $post->title]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.deleted');
    }
}
