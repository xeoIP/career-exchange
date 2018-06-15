<?php

namespace App\Http\Requests;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;

class SendMessageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                 => 'required|mb_between:2,200',
            'email'                => 'max:100',
            'phone'                => 'max:20',
            'message'              => 'required|mb_between:20,500',
            'post'                 => 'required|numeric',
            'g-recaptcha-response' => (config('settings.activation_recaptcha')) ? 'required' : '',
        ];
    
        // Check 'resume' is required
        if (Auth::check()) {
            $resume = Resume::where('user_id', Auth::user()->id)->first();
            if (empty($resume) or trim($resume->filename) == '' or !file_exists(public_path($resume->filename))) {
                $rules['filename'] = 'required|mimes:' . getUploadFileTypes('file') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
            }
        } else {
            $rules['filename'] = 'required|mimes:' . getUploadFileTypes('file') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
        }
    
        // Email
        if ($this->filled('email')) {
            $rules['email'] = 'email|' . $rules['email'];
        }
        if (isEnabledField('email')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                $rules['email'] = 'required_without:phone|' . $rules['email'];
            } else {
                $rules['email'] = 'required|' . $rules['email'];
            }
        }
    
        // Phone
        if ($this->filled('phone')) {
            $rules['phone'] = 'phone:' . $this->input('country', config('country.code')) . ',mobile|' . $rules['phone'];
        }
        if (isEnabledField('phone')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                $rules['phone'] = 'required_without:email|' . $rules['phone'];
            } else {
                $rules['phone'] = 'required|' . $rules['phone'];
            }
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
