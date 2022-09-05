<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ScheduleEndValidation extends ApiRequest
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
                    "endLatitude" => 'nullable|between:-90,90',
                    "endLongitude" => 'nullable|between:-180,180',
                    "endLocation" => 'required|max:255',
                    "uploadPhoto" => 'nullable|max:255',
                    "voiceRecording" => 'nullable|max:255',

                // "uploadPhoto" => 'nullable|max:255',
                // "voiceRecording" => 'nullable|max:255',

            ];


            return $result;
    }
}
