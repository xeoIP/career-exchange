<?php

namespace App\Http\Requests;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Check if these fields has changed
        $emailChanged = ($this->input('email') != Auth::user()->email);
        $phoneChanged = ($this->input('phone') != Auth::user()->phone);
        $usernameChanged = ($this->filled('username') && $this->input('username') != Auth::user()->username);
    
        // Validation Rules
        $rules = [];
        if (empty(Auth::user()->user_type_id) || Auth::user()->user_type_id == 0) {
            $rules['user_type'] = 'required|not_in:0';
        } else {
            $rules['gender']    = 'required|not_in:0';
            $rules['name']      = 'required|max:100';
            $rules['phone']     = 'required|phone:' . $this->input('country', config('country.code')) . ',mobile|max:20';
            $rules['email']     = 'required|email|whitelist_email|whitelist_domain';
            $rules['username']  = 'valid_username|allowed_username|between:3,100';
        
            if ($phoneChanged) {
                $rules['phone'] = 'unique:users,phone|' . $rules['phone'];
            }
            if ($emailChanged) {
                $rules['email'] = 'unique:users,email|' . $rules['email'];
            }
            if ($usernameChanged) {
                $rules['username'] = 'required|unique:users,username|' . $rules['username'];
            }
        }
    
        // Check 'resume' is required
        if ($this->hasFile('filename')) {
            $rules['filename'] = 'required|mimes:' . getUploadFileTypes('file') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
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
