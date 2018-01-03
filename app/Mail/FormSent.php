<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSent extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;

    /**
     * Create a new message instance.
     *
     * @param $request
     * @param $recipient
     */
    public function __construct($request, $recipient)
    {
        $this->msg = $request;

		$this->to($recipient->email, $recipient->name);
        $this->replyTo($request->email, $request->first_name . ' ' . $request->last_name);
        $this->subject(trans('mail.contact_form_title', [
            'country'   => $request->country,
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
        return $this->view('emails.form');
    }
}
