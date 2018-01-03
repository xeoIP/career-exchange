<?php

namespace App\Http\Requests\Admin;

class PageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required|min:2|max:255',
            'title'   => 'required|min:2|max:255',
            'content' => 'required',
        ];
    }
}
