<?php

namespace App\Http\Controllers\Auth\Traits;


use App\Notifications\PhoneVerification;
use Illuminate\Support\Facades\Request;
use Prologue\Alerts\Facades\Alert;

trait PhoneVerificationTrait
{
	/**
	 * Send verification SMS
	 *
	 * @param $entity
	 * @param bool $displayFlashMessage
	 * @return bool
	 */
	public function sendVerificationSms($entity, $displayFlashMessage = true)
	{
		// Get Entity
		$entityRef = $this->getEntityRef();
		if (empty($entity) || empty($entityRef)) {
            $message = t("Entity ID not found.");
            
            if ($this->isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
			return false;
		}
		
		// Send Confirmation Email
		try {
			$entity->notify(new PhoneVerification($entity, $entityRef));
			
			if ($displayFlashMessage) {
				$message = t("An activation code has been sent to you to verify your phone number.");
				flash($message)->success();
			}
			
			session(['verificationSmsSent' => true]);
			
			return true;
		} catch (\Exception $e) {
            if ($this->isFromAdminPanel()) {
                Alert::error($e->getMessage())->flash();
            } else {
                flash($e->getMessage())->error();
            }
		}
		
		return false;
	}
	
	/**
	 * Show the ReSend Verification SMS Link
	 *
	 * @param $entity
	 * @param $entityRefId
	 * @return bool
	 */
	public function showReSendVerificationSmsLink($entity, $entityRefId)
	{
		// Get Entity
		$entityRef = $this->getEntityRef($entityRefId);
		if (empty($entity) || empty($entityRef)) {
			return false;
		}
		
		// Show ReSend Verification SMS Link
		if (session()->has('verificationSmsSent')) {
			$message = t("Resend the verification message to verify your phone number.");
			$message .= ' <a href="' . lurl('verify/' . $entityRef['slug'] . '/' . $entity->id . '/resend/sms') . '" class="btn btn-warning">' . t("Re-send") . '</a>';;
			flash($message)->warning();
		}
		
		return true;
	}
	
	/**
	 * URL: Re-Send the verification SMS
	 *
	 * @param $entityId
	 * @return \Illuminate\Http\RedirectResponse
	 */
    public function reSendVerificationSms($entityId)
    {
        // Non-admin data resources
        $entityRefId = getSegment(2);
        
        // Admin data resources
        if ($this->isFromAdminPanel()) {
            $entityRefId = Request::segment(3);
        }
        
        // Keep Success Message If exists
        if (session()->has('message')) {
            session()->keep(['message']);
        }
        
        // Get Entity
        $entityRef = $this->getEntityRef($entityRefId);
        if (empty($entityRef)) {
            $message = t("Entity ID not found.");
            
            if ($this->isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
            return back();
        }
        
        // Get Entity by Id
        $model = $entityRef['namespace'];
        $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
        if (empty($entity)) {
            $message = t("Entity ID not found.");
            
            if ($this->isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
            return back();
        }
        
        // Check if the Phone is already verified
        if ($entity->verified_phone == 1 || isDemoAdmin()) {
            if (isDemoAdmin()) {
                $message = t("This feature has been turned off in demo mode.");
            } else {
                $message = t("Your :field is already verified.", ['field' => t('Phone Number')]);
            }
            
            if ($this->isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }
            
            // Remove Notification Trigger
            $this->clearSmsSession();
            
            return back();
        }
        
        // Re-Send the confirmation
        if ($this->sendVerificationSms($entity, false)) {
            if ($this->isFromAdminPanel()) {
                $message = t("The activation code has been sent to the user to verify his phone number.");
                Alert::success($message)->flash();
            } else {
                $message = t("The activation code has been sent to you to verify your phone number.");
                flash($message)->success();
            }
            
            // Remove Notification Trigger
            $this->clearSmsSession();
        }
        
        return back();
    }
    
    /**
     * Remove Notification Trigger (by clearing the sessions)
     */
    private function clearSmsSession()
    {
        if (session()->has('verificationSmsSent')) {
            session()->forget('verificationSmsSent');
        }
    }
}
