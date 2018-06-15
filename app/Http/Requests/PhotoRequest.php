<?php

namespace App\Http\Requests;

class PhotoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
    
        // Require 'pictures' if exists
        if ($this->file('pictures')) {
            $files = $this->file('pictures');
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $rules['pictures.' . $key] = 'required|image|mimes:' . getUploadFileTypes('image') . '|max:' . (int)config('settings.upload_max_file_size', 1000);
                }
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
