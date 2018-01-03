<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;

class PostReviewed extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;

        $this->to($post->email, $post->contact_name);
        $this->subject(trans('mail.post_reviewed_title', ['title' => str_limit($post->title, 50)]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.reviewed');
    }
}
