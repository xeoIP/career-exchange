<?php

namespace App\Http\Requests\Admin;

class CountryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'           => 'required|min:2|max:2',
            'name'           => 'required|min:3|max:255',
            'asciiname'      => 'required',
            'continent_code' => 'required',
            'currency_code'  => 'required',
            'phone'          => 'required',
            'languages'      => 'required',
        ];
    }
}
