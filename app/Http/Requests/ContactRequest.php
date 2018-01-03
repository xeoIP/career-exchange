<?php

namespace App\Http\Requests;

class ContactRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name'           => 'required|mb_between:2,100',
            'last_name'            => 'required|mb_between:2,100',
            'email'                => 'required|email|whitelist_email|whitelist_domain',
            'message'              => 'required|mb_between:5,500',
            'g-recaptcha-response' => (config('settings.activation_recaptcha')) ? 'required' : '',
        ];
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];
        
        return $messages;
    }
}
