<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;

class PaymentSent extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $post;

    /**
     * PaymentSent constructor.
     * @param Payment $payment
     * @param Post $post
     */
    public function __construct(Payment $payment, Post $post)
    {
        $this->payment = $payment;
        $this->post = $post;

        $this->to($post->email, $post->contact_name);
        $this->subject(trans('mail.payment_sent_title'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.payment.sent');
    }
}
