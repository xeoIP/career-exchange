<?php

namespace App\Models\Traits;


trait VerifiedTrait
{
    public function getVerifiedEmailHtml()
    {
        if (!isset($this->verified_email)) return false;
        
        // Get checkbox
        $out = ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'verified_email', $this->verified_email);
        
        // Get all entity's data
        $entity = self::find($this->{$this->primaryKey});
        
        if (!empty($entity->email)) {
            if ($entity->verified_email != 1) {
                // Show re-send verification message link
                $entitySlug = ($this->getTable() == 'users') ? 'user' : 'post';
                $urlPath = 'verify/' . $entitySlug . '/' . $this->{$this->primaryKey} . '/resend/email';
                $toolTip = (!empty($entity->email)) ? 'data-toggle="tooltip" title="' . $entity->email . '"' : '';
                $out .= ' - [<a href="' . url(config('larapen.admin.route_prefix', 'admin') . '/' . $urlPath) . '" ' . $toolTip . '>'. __t('Re-send link') . '</a>]';
            } else {
                // Get social icon (if exists)
                if ($this->getTable() == 'users') {
                    if (!empty($entity) && isset($entity->provider)) {
                        if (!empty($entity->provider)) {
                            if ($entity->provider == 'facebook') {
                                $toolTip = 'data-toggle="tooltip" title="' . __t('Registered with :provider', ['provider' => 'Facebook']) . '"';
                                $out .= ' - <i class="fa fa-facebook-square" style="color: #3b5998;" ' . $toolTip . '></i>';
                            }
                            if ($entity->provider == 'google') {
                                $toolTip = 'data-toggle="tooltip" title="' . __t('Registered with :provider', ['provider' => 'Google']) . '"';
                                $out .= ' - <i class="fa fa-google-plus-square" style="color: #d34836;" ' . $toolTip . '></i>';
                            }
                        }
                    }
                }
            }
        } else {
            $out = checkboxDisplay($this->verified_email);
        }
        
        return $out;
    }
    
    public function getVerifiedPhoneHtml()
    {
        if (!isset($this->verified_phone)) return false;
        
        // Get checkbox
        $out = ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'verified_phone', $this->verified_phone);
        
        // Get all entity's data
        $entity = self::find($this->{$this->primaryKey});
        
        if (!empty($entity->phone)) {
            if ($entity->verified_phone != 1) {
                // Show re-send verification message code
                $entitySlug = ($this->getTable() == 'users') ? 'user' : 'post';
                $urlPath = 'verify/' . $entitySlug . '/' . $this->{$this->primaryKey} . '/resend/sms';
                $toolTip = (!empty($entity->phone)) ? 'data-toggle="tooltip" title="' . $entity->phone . '"' : '';
                $out .= ' - [<a href="' . url(config('larapen.admin.route_prefix', 'admin') . '/' . $urlPath) . '" ' . $toolTip . '>'. __t('Re-send code') . '</a>]';
            }
        } else {
            $out = checkboxDisplay($this->verified_phone);
        }
        
        return $out;
    }
}