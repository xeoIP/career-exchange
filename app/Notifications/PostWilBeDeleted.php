<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Post;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class PostWilBeDeleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;
    protected $days;

    public function __construct(Post $post, $days)
    {
        $this->post = $post;
        $this->days = $days;
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
        return (new \App\Mail\PostWilBeDeleted($this->post, $this->days));
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
        return trans('sms.post_will_be_deleted_content', ['app_name' => config('app.name'), 'title' => $this->post->title, 'days' => $this->days]);
    }
}
