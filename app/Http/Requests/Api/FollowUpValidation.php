<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class FollowUpValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $fDateTime = $request->fDate .' '.$request->fTime;

        $result =   [
                "rjDealerId" => "nullable|max:36",
                "fPurpose" => "required",
                "fDate" => "nullable|date_format:Y-m-d",
                // "fTime" => "nullable|date_format:H:i",
            ];


            return $result;
    }
}
