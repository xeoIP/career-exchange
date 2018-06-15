<?php

namespace App\Http\Requests;

class ReportRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'report_type'          => 'required|not_in:0',
            'email'                => 'required|email|max:100',
            'message'              => 'required|mb_between:20,1000',
            'post'                 => 'required|numeric',
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
