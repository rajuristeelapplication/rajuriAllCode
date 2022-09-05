<?php

namespace App\Http\Requests\Api;

class Register extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "firstName" => 'required|max:100',
            "lastName" => 'required|max:100',
            "email" => "required|email|unique:users,email",
            "mobileNumber" => "max:16|unique:users,mobileNumber",
            "roleId" => 'required|in:SE,ME',
            "address" => 'required',
            "photoIdProof" => 'required',
            "zipCode" => 'required',
            "cityId" => 'required',
            // "countryCode" => 'required',
            "timezone" => 'required',
        ];
    }
}
