<?php

namespace App\Providers;

use App\Helpers\Validator\BlacklistValidator;
use App\Helpers\Validator\GlobalValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('whitelist_domain', function ($attribute, $value) {
            return BlacklistValidator::checkDomain($value);
        });
        
        Validator::extend('whitelist_email', function ($attribute, $value) {
            return BlacklistValidator::checkEmail($value);
        });
        
        Validator::extend('whitelist_word', function ($attribute, $value) {
            return BlacklistValidator::checkWord($value);
        });
        
        Validator::extend('whitelist_word_title', function ($attribute, $value) {
            return BlacklistValidator::checkTitle($value);
        });
        
        Validator::extend('mb_between', function ($attribute, $value, $parameters, $validator) {
            return GlobalValidator::mbBetween($value, $parameters);
        });
        Validator::replacer('mb_between', function($message, $attribute, $rule, $parameters) {
            $min = (isset($parameters[0])) ? (int)$parameters[0] : 0;
            $max = (isset($parameters[1])) ? (int)$parameters[1] : 999999;
            return str_replace([':min', ':max'], [$min, $max], $message);
        });

        Validator::extend('valid_username', '\App\Helpers\Validator\UsernameValidator@isValid');
        Validator::extend('allowed_username', '\App\Helpers\Validator\UsernameValidator@isAllowed');
    }
    
    public function register()
    {
        //
    }
}
