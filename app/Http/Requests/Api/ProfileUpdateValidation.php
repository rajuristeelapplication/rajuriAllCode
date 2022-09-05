<?php

namespace App\Http\Requests\Api;

class ProfileUpdateValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = \Auth::user();

        return [
            "firstName" => 'required|max:100',
            "lastName" => 'required|max:100',
            "email" => "required|email|unique:users,email,".$user->id,
            "mobileNumber" => "max:16|unique:users,mobileNumber,".$user->id,
            "address" => 'required',
            "photoIdProof" => 'required',
            "zipCode" => 'required',
            "cityId" => 'required',

        ];
    }
}
