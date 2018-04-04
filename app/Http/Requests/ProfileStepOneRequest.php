<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileStepOneRequest extends FormRequest
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

        return [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|string|email|unique:users,email,' . $this->get('id'),
            'phone_number'      => 'required|string|max:50',
            'social_security'   => 'required|string|min:9',
            'city'              => 'required|exists:cities,name',
            'photoInput'        => 'mimes:jpeg,jpg,png|max:50000',
        ];
    }
}
