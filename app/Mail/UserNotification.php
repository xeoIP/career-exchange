<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * UserNotification constructor.
     * @param $user
     * @param $adminUser
     */
    public function __construct($user, $adminUser)
    {
        $this->user = $user;

        $this->to($adminUser->email, $adminUser->name);
        $this->subject(trans('mail.user_notification_title'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.notification');
    }
}
