<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileStepTwoRequest extends FormRequest
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
            //
        ];
    }

    public function getPrepared()
    {
        $data = $this->all();

        if ($data['position'] == 0) {
            unset($data['position']);
        }

        $skills = [];
        $additionalSkills = [];
        for ($skillsCounter = 1; $skillsCounter <= 5; $skillsCounter++) {
            $getSkill = 'skill_' . $skillsCounter;
            $getAdditionalSkill = 'additional_skill_' . $skillsCounter;
            if (!empty($data[$getSkill][0])) {
                $skills[] = $data[$getSkill];
            }
            unset($data[$getSkill]);

            $additionalSkill = isset($data[$getAdditionalSkill]) ? $data[$getAdditionalSkill] : null;
            if (!empty($additionalSkill[0])) {
                $additionalSkills[] = $data[$getAdditionalSkill];
            }
            unset($data[$getAdditionalSkill]);
        }
        $data['role_experience'] = isset($data['role_experience']) ? $data['role_experience'] : [];
        $data['skills'] = $skills;
        $data['additionalSkills'] = $additionalSkills;

        return $data;
    }
}
