<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Post;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PaymentSent extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;
    protected $post;

    public function __construct(Payment $payment, Post $post)
    {
        $this->payment = $payment;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        if (!empty($this->post->email)) {
            return ['mail'];
        } else {
            if (config('settings.sms_driver') == 'twilio') {
                return [TwilioChannel::class];
            }

            return ['nexmo'];
        }
    }

    public function toMail($notifiable)
    {
        return (new \App\Mail\PaymentSent($this->payment, $this->post));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())->content($this->smsMessage())->unicode();
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())->content($this->smsMessage());
    }

    protected function smsMessage()
    {
        return trans('sms.payment_sent_content', ['app_name' => config('app.name'), 'title' => $this->post->title]);
    }
}
