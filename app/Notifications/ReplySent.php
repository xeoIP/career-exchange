<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class ReplySent extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $replyForm;

    public function __construct(Message $message, $replyForm)
    {
        $this->message = $message;
        $this->replyForm = $replyForm;
    }

    public function via($notifiable)
    {
        if (!empty($this->message->email)) {
            if (config('settings.sms_message_activation') == 1) {
                if (!empty($this->message->phone)) {
                    if (config('settings.sms_driver') == 'twilio') {
                        return ['mail', TwilioChannel::class];
                    }
        
                    return ['mail', 'nexmo'];
                }
    
                return ['mail'];
            } else {
                return ['mail'];
            }
        } else {
            if (config('settings.sms_driver') == 'twilio') {
                return [TwilioChannel::class];
            }

            return ['nexmo'];
        }
    }

    public function toMail($notifiable)
    {
        return (new \App\Mail\ReplySent($this->message, $this->replyForm));
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
        return trans('sms.reply_form_content', [
            'app_name' => config('app.name'),
            'adTitle'  => $this->replyForm->post_title,
            'message'  => str_limit(strip_tags($this->replyForm->message), 50)
        ]);
    }
}
