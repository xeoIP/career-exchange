<?php

namespace App\Mail;

use App\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $package;
    public $post;

    /**
     * PaymentNotification constructor.
     * @param $payment
     * @param $post
     * @param $adminUser
     */
    public function __construct($payment, $post, $adminUser)
    {
        $this->payment = $payment;
        $this->package = Package::find($payment->package_id);
        $this->post = $post;

        $this->to($adminUser->email, $adminUser->name);
        $this->subject(trans('mail.payment_notification_title'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payment.notification');
    }
}
