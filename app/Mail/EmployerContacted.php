<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Models\Message;

class EmployerContacted extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $msg;
    public $pathToFile;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     * @param Message $msg
     * @param $pathToFile
     */
    public function __construct(Post $post, Message $msg, $pathToFile)
    {
        $this->post = $post;
        $this->msg = $msg;
        $this->pathToFile = $pathToFile;

        $this->to($post->email, $post->contact_name);
        $this->replyTo($msg->email, $msg->name);
        $this->subject(trans('mail.post_employer_contacted_title', [
            'title' => $post->title,
            'app_name' => config('settings.app_name')
        ]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Attachments
        if (file_exists($this->pathToFile)) {
            return $this->view('emails.post.employer-contacted')->attach($this->pathToFile);
        } else {
            return $this->view('emails.post.employer-contacted');
        }
    }
}
