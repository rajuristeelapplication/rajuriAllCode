<?php

namespace App\Http\Requests\Api;

class ChangePassword extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8|max:16',
            'confirmPassword' => 'required|min:8|max:16|same:newPassword'
        ];
    }

}
