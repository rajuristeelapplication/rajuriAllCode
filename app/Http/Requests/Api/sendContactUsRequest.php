<?php

namespace App\Http\Requests\Api;

class sendContactUsRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'departmentId' => 'required|max:36',
            'headDepartmentId' => 'required|max:36',
            'email' => 'required|email',
            'mobileNumber' => 'required|digits:10',
            // 'message' => 'required'
        ];
    }
}
