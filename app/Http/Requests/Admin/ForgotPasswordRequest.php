<?php

namespace App\Http\Requests\Admin;

class ForgotPasswordRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required',
        ];
    
        // Recaptcha
        if (config('settings.activation_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required';
        }
        
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
