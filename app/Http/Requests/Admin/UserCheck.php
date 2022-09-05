<?php

namespace App\Http\Requests\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class UserCheck extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => 'required|email|unique:users,email,'.$request->id,
            'mobileNumber' => 'required|unique:users,mobileNumber,'.$request->id,
            'area' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'pinCode' => ['required', 'string', 'max:100'],
            'bankName' => ['required', 'string', 'max:100'],
            'bankAccountNumber' => ['required', 'string', 'max:100'],

        ];
    }
}
