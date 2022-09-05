<?php

namespace App\Http\Requests\Api;

use App\Models\InOut;

class InOutValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $user = \Auth::user();
        $status = InOut::checkStatus($user->id);


        if($status['status'] == 0)
        {
            return [
                "inAddress" => 'required|max:255',
                "inLatitude" => 'required|between:-90,90',
                "inLongitude" => 'required|between:-180,180',
                "inReadingPhoto" => "required",
                // "inDateTime" => "required|date_format:Y-m-d H:i:s",
            ];
        }
        elseif($status['status'] == 1)
        {
            return [
                "outAddress" => 'required|max:255',
                "outLatitude" => 'required|between:-90,90',
                "outLongitude" => 'required|between:-180,180',
                "outReadingPhoto" => "required",
                // "outDateTime" => "required|date_format:Y-m-d H:i:s",
            ];
        }else{
            return [];
        }

    }
}
