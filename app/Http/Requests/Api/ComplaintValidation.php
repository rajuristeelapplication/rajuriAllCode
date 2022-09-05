<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ComplaintValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "cType" => 'required|in:Quality Complaint,General Complaint',
                "rjDealerId" => "required|max:36",
                "cDate" => "nullable|date_format:Y-m-d",
                // "cTime" => "nullable|date_format:H:i",
                "cSiteLocation" => "nullable|max:255",
                "cSiteLatitude" => 'nullable|between:-90,90',
                "cSiteLongitude" => 'nullable|between:-180,180',
                "cAddress" => "nullable|max:255",
                "cStateId" => "nullable|max:255",
                "cSName" => "nullable|max:255",
                "cCityId" => "nullable|max:255",
                "cCName" => "nullable|max:255",
                "cTalukaId" => "nullable|max:255",
                "cTName" => "nullable|max:255",
                "cPinCode" => "nullable|max:255",
                "cWpMobileNumber" => "nullable|digits:10",
                "cMobileNumber" => "nullable|digits:10",
                "cEmail" => "nullable|email",
                "cProductNameBillingDetails" => "nullable|max:255",
                "cTotalQty" => "nullable|max:255",
                "cLotNumber" => "nullable|max:255",
                "cTruckNumber" => "nullable|max:255",
                "cPhotoVideo" => "nullable|max:255",
            ];

            // if($request->cType == 'General Complaint')
            // {
            //     $result['complaintType'] = 'required|in:Payment,Dispatch,Product,Freight,Others';
            // }


            return $result;
    }
}
