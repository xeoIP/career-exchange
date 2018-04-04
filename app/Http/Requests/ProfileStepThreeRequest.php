<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileStepThreeRequest extends FormRequest
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


    public function messages()
    {
        $message = [];

        foreach ($this->request->get('locations') as $key => $value) {
            $message += ['locations.' . $key.'*' => "please choose city input number ". $key ." from suggested list" ];
        }

        return $message;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'date_available'        => 'required|date',
            'additional_info'       => 'required|string',
            'current_base_salary'   => 'sometimes|nullable|numeric',
            'current_contract_rate' => 'sometimes|nullable|numeric',
            'target_base_salary'    => 'sometimes|nullable|numeric',
            'target_contract_rate'  => 'sometimes|nullable|numeric',
            'locations.*'           => 'sometimes|nullable|exists:cities,name',
        ];

        return $rules;
    }

    public function getBoolean($parameter)
    {
        return $this->get($parameter) === '1' ? true : false;
    }

    /**
     * @return array|mixed
     */
    public function getLocations()
    {
        $locations = $this->get('locations')[0] != null ? $this->get('locations') : [];

        foreach ($locations as $key => $location) {
            if ($location === null) {
                unset($locations[$key]);
            }
        }

        return $locations;
    }
}

