<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $entity;
	protected $entityRef;
	
	public function __construct($entity, $entityRef)
	{
		$this->entity = $entity;
		$this->entityRef = $entityRef;
	}
	
	public function via($notifiable)
	{
		if (!isset($this->entityRef['name'])) {
			return false;
		}
		
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		return (new \App\Mail\EmailVerification($this->entity, $this->entityRef));
	}
}
