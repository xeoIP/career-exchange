<?php

namespace App\Http\Requests\Admin;

class TimeZoneRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_code' => 'required',
            'time_zone_id' => 'required',
            'gmt'          => 'required',
            'dst'          => 'required',
        ];
    }
}
