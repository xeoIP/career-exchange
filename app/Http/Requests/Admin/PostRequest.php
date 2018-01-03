<?php

namespace App\Http\Requests\Admin;

class PostRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'         => 'required|not_in:0',
            'post_type_id'        => 'required|not_in:0',
            'company_name'        => 'required|mb_between:10,200|whitelist_word_title',
            'company_description' => 'required|mb_between:10,3000|whitelist_word',
            'title'               => 'required|between:5,200',
            'description'         => 'required|between:5,3000',
            'contact_name'        => 'required|between:3,200',
            'email'               => 'required|email|max:100',
        ];
    }
}
