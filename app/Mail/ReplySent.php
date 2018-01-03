<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplySent extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
	public $replyForm;

    /**
     * Create a new message instance.
     *
	 * @param Message $message
	 * @param $replyForm
	 */
    public function __construct(Message $message, $replyForm)
    {
        $this->message = $message;
		$this->replyForm = $replyForm;

        $this->to($message->email, $message->name);
        $this->replyTo($replyForm->sender_email, $replyForm->sender_name);
        $this->subject(trans('mail.reply_form_title', [
            'postTitle' => $replyForm->post_title,
            'app_name'  => config('settings.app_name')
        ]));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.post.reply-sent');
    }
}
