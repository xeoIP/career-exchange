<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileStoreRequest extends FormRequest
{
    const FILL_FORM_TYPE = 'fill';
    const IMPORT_FORM_TYPE = 'import';

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
            'company.*'     => 'sometimes|nullable|string',
            'title.*'       => 'sometimes|nullable|string',
            'university.*'  => 'sometimes|nullable|string',
            'degree.*'      => 'sometimes|nullable|string',
            'start_date.*'  => 'sometimes|nullable|date',
            'end_date.*'    => 'sometimes|nullable|date',
            'degree_date.*' => 'sometimes|nullable|numeric'
        ];
    }

    public function getPreparedData()
    {
        $data = $this->all();

        $experienceCount = $data['company'][0] === null ? 0 : count($data['company']);
        $experiences = [];

        for ($counter = 0; $counter < $experienceCount; $counter++) {


            $experiences[] = [
                'company'   => $data['company'][$counter],
                'title'     => isset($data['title'][$counter]) ? $data['title'][$counter] : null,
                'start_date'=> isset($data['start_date'][$counter]) ? $data['start_date'][$counter] : null,
                'end_date'  => isset($data['end_date'][$counter]) ? $data['end_date'][$counter] : null,
                'current' => isset($data['current'][$counter]) ? $data['current'][$counter] : 0
            ];
        }

        $educationCount = $data['university'][0] === null ? 0 : count($data['university']);
        $educations = [];

        for ($counter = 0; $counter < $educationCount; $counter++) {
            $educations[] = [
                'university'    => $data['university'][$counter],
                'degree'        => isset($data['degree'][$counter]) ? $data['degree'][$counter] : null,
                'degree_date'   => isset($data['degree_date'][$counter]) ? $data['degree_date'][$counter] : null
            ];
        }
        return [
            'experiences'   => $experiences,
            'educations'    => $educations
        ];
    }
}
