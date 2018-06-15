<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $cat = null;
        
        // CREATE
        if (in_array($this->method(), ['POST', 'CREATE'])) {
            $rules = [
                'parent'              => 'required|not_in:0',
                'category'            => 'required|not_in:0',
                'post_type'           => 'required|not_in:0',
                'company_name'        => 'required|mb_between:2,200|whitelist_word_title',
                'company_description' => 'required|mb_between:5,1000|whitelist_word',
                'title'               => 'required|mb_between:2,200|whitelist_word_title',
                'description'         => 'required|mb_between:5,2000|whitelist_word',
                'salary_type'         => 'required|not_in:0',
                'contact_name'        => 'required|mb_between:2,200',
                'email'               => 'max:100|whitelist_email|whitelist_domain',
                'phone'               => 'max:20',
                'city'                => 'required|not_in:0',
            ];
            
            // Check 'logo' is required
            if ($this->file('logo')) {
                $rules['logo'] = 'required|image|mimes:' . getUploadFileTypes('image') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
            }
            
            // Recaptcha
            if (config('settings.activation_recaptcha')) {
                $rules['g-recaptcha-response'] = 'required';
            }
        }
        
        // UPDATE
        if (in_array($this->method(), ['PUT', 'PATCH', 'UPDATE'])) {
            $rules = [
                'category'            => 'required|not_in:0',
                'post_type'           => 'required|not_in:0',
                'company_name'        => 'required|mb_between:2,200|whitelist_word_title',
                'company_description' => 'required|mb_between:50,1000|whitelist_word',
                'title'               => 'required|mb_between:10,200|whitelist_word_title',
                'description'         => 'required|mb_between:50,2000|whitelist_word',
                'salary_type'         => 'required|not_in:0',
                'contact_name'        => 'required|mb_between:2,200',
                'email'               => 'max:100|whitelist_email|whitelist_domain',
                'phone'               => 'max:20',
                'city'                => 'required|not_in:0',
            ];
            
            // Check 'logo' is required
            if ($this->file('logo')) {
                $rules['logo'] = 'required|image|mimes:' . getUploadFileTypes('image') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
            }
        }
        
        // COMMON
        
        // Location
        if (in_array(config('country.admin_type'), ['1', '2']) && config('country.admin_field_active') == 1) {
            $rules['admin_code'] = 'required|not_in:0';
        }
        
        // Email
        if ($this->filled('email')) {
            $rules['email'] = 'email|' . $rules['email'];
        }
        if (isEnabledField('email')) {
            if (isEnabledField('phone') && isEnabledField('email')) {
                if (Auth::check()) {
                    $rules['email'] = 'required_without:phone|' . $rules['email'];
                } else {
                    // Email address is required for Guests
                    $rules['email'] = 'required|' . $rules['email'];
                }
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
