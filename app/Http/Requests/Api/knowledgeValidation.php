<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class knowledgeValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "rjDealerId" => "required|max:36",
                "kdate" => "nullable|date_format:Y-m-d",
                "ktime" => "nullable|date_format:H:i",
                "kCurrentLocation" => "required|max:255",
                "kCurrentLatitude" => 'required|between:-90,90',
                "kCurrentLongitude" => 'required|between:-180,180',
                "kStartLocation" => "nullable|max:255",
                "kStartLatitude" => "nullable|max:255",
                "kStartLongitude" => "nullable|max:255",
                "ksStateId" => "nullable|max:255",
                "ksSName" => "nullable|max:255",
                "ksCityId" => "nullable|max:255",
                "ksCName" => "nullable|max:255",
                "ksTalukaId" => "nullable|max:255",
                "ksTName" => "nullable|max:255",
                "ksPinCode" => "nullable|max:255",
                "kDestination" => "nullable|max:255",
                "kDestinationLatitude" => "nullable|max:255",
                "kDestinationLongitude" => "nullable|max:255",
                "kdStateId" => "nullable|max:255",
                "kdSName" => "nullable|max:255",
                "kdCityId" => "nullable|max:255",
                "kdCName" => "nullable|max:255",
                "kdTalukaId" => "nullable|max:255",
                "kdTName" => "nullable|max:255",
                "kdPinCode" => "nullable|max:255",
            ];

            return $result;
    }
}
