<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ScheduleStartValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                     "id" => 'required|max:36',
                    "startLocation" => 'required|max:255',
                    "startLatitude" => 'nullable|between:-90,90',
                    "startLongitude" => 'nullable|between:-180,180',
                    "uploadPhoto" => 'nullable|max:255',
                    "voiceRecording" => 'nullable|max:255',

            ];


            return $result;
    }
}
