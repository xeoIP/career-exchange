<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileStepFourRequest extends FormRequest
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
            'linkedIn'      => 'sometimes|nullable|active_url',
            'github'        => 'sometimes|nullable|active_url',
            'stackOverflow' => 'sometimes|nullable|active_url',
            'website'       => 'sometimes|nullable|active_url',
            'resume'        => 'sometimes|nullable|active_url',
            'twitter'       => 'sometimes|nullable|active_url',
        ];
    }
}
