<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;

        $this->to($user->email, $user->name);
        $this->subject(trans('mail.reset_password_title'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password');
    }
}
