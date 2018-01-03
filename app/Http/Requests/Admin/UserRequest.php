<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (is_numeric(Request::segment(3))) {
            $uniqueEmailIsRequired = true;

            $user = User::find(Request::segment(3));
            if (!is_null($user)) {
                if ($user->email == $this->email) {
                    $uniqueEmailIsRequired = false;
                }
            }

            return [
                'gender_id'    => 'required|not_in:0',
                'name'         => 'required|min:3|max:100',
                'country_code' => 'sometimes|required|not_in:0',
                'email'        => ($uniqueEmailIsRequired) ? 'required|email|unique:users,email' : 'required|email',
                //'password' => 'required|between:5,15',
            ];
        } else {
            return [
                'gender_id'    => 'required|not_in:0',
                'name'         => 'required|min:3|max:100',
                'user_type_id' => 'required|not_in:0',
                'country_code' => 'sometimes|required|not_in:0',
                'email'        => 'required|email|unique:users,email',
                //'password' => 'required|between:5,15',
            ];
        }
    }
}
