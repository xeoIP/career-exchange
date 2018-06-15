<?php

namespace App\Http\Requests\Admin;

class PackageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|min:2|max:255',
            'short_name'    => 'required|min:2|max:255',
            'price'         => 'required|numeric',
            'currency_code' => 'required',
        ];
    }
}
