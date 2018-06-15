<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
	use Queueable, SerializesModels;
	
	public $entity;
	public $entityRef;
	
	/**
	 * EmailVerification constructor.
	 *
	 * @param $entity
	 * @param $entityRef
	 */
	public function __construct($entity, $entityRef)
	{
		$this->entity = $entity;
		$this->entityRef = $entityRef;
		
		$this->to($entity->email, $entity->{$entityRef['name']});
		$this->subject(trans('mail.email_verification_title', ['app_name' => config('settings.app_name')]));
	}
	
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('emails.verification');
	}
}
