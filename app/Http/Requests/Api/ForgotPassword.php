<?php

namespace App\Http\Requests\Api;

class ForgotPassword extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "userName" => "required",
        ];
    }

}
