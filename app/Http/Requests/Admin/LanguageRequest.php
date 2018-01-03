<?php

namespace App\Http\Requests\Admin;

class LanguageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required|min:3|max:255',
            'native' => 'required|min:3|max:255',
            'abbr'   => 'required|min:2|max:2',
            'locale' => 'required|min:5|max:20',
        ];
    }
}
