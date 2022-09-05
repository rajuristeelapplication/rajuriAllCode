<?php

namespace App\Http\Requests\Api;

class UploadFile extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:profile,photoid,inout,dealer,schedule,knowledge,complaint,reimbursement,voice_recording,merchandises',
            // 'file' => 'required|mimes:jpg,jpeg,png,mp3,m4a,aac|max:10240',
            'file' => 'required|max:10240',
        ];
    }
}
