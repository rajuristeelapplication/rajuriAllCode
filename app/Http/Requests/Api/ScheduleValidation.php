<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ScheduleValidation extends ApiRequest
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
                "sType" => 'required|in:Dealer Visit,Site Visit,Influencer Visit',
                "sDate" => 'required|date_format:Y-m-d',
                // "sTime" => 'required|date_format:H:i',
                "sFirmName" => "required|max:255",
                "purpose" => "required",
                "slocation" => "nullable|max:255",
                "slatitude" => 'nullable|between:-90,90',
                "slongitude" => 'nullable|between:-180,180',
                "uploadPhoto" => "nullable|max:255",
                "voiceRecording" => "nullable|max:255",
                "dAddress1" => "nullable|max:255",
                "dAddress2" => "nullable|max:255",
                "dStateId" => "nullable|max:36",
                "dSName" => "nullable|max:255",
                "dCityId" => "nullable|max:36",
                "dCName" => "nullable|max:255",
                "dTalukaId" => "nullable|max:36",
                "dTName" => "nullable|max:255",
                "dPinCode" => "nullable|max:6",
                "dRegionId" => "nullable|max:36",
                "dRName" => "nullable|max:255",
                "dWpMobileNumber" => "nullable|max:15",
                "dMobileNumber" => "nullable|max:15",
                "brandUsed" => "nullable|max:255",
                "construction" => "nullable|max:255",
                "ourMaterialsAvailable" => "nullable|max:255",
                "dTotalQty" => "nullable|max:255",
                "competitorActivitiesText" => "nullable|max:255",
                "competitorActivitiesImage" => "nullable|max:255",
                "typeOfSite" => "nullable|max:255",
                "statusOfSite" => "nullable|max:255",
                "area" => "nullable|max:255",
                "estimatedCost" => "nullable|max:255",
                "projectEngineer" => "nullable|max:255",
                "architect" => "nullable|max:255",
                "executive" => "nullable|max:255",
                "usedTillNow" => "nullable|max:255",
                "tentativeSchedule" => "nullable|max:255",
                "completedProject" => "nullable|max:255",
                "ongoingProject" => "nullable|max:255",
                "anyLead" => "nullable|max:255",
                "additionVisit" => "nullable|max:255",
            ];


            return $result;
    }
}
