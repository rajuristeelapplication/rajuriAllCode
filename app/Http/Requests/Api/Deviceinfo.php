<?php

namespace App\Http\Requests\Api;

class Deviceinfo extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deviceType' => 'required|in:IOS,ANDROID,WEB',
            'deviceToken' => 'required',
            'fcmToken' => 'required_if:deviceType,IOS,ANDROID',
        ];
    }
}
