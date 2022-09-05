<?php

namespace App\Http\Requests\Api;

class VerifyOTP extends ApiRequest
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
            'otp' => 'required|size:4',
        ];
    }
}
