<?php

namespace App\Http\Requests\Api;

class Reset extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userName' => 'required',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|min:6|same:password',
        ];
    }
}
