<?php

namespace App\Http\Requests\Admin;

class PaymentMethodRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required|min:2|max:255',
            'description'  => 'required|min:2|max:255',
        ];
    }
}
