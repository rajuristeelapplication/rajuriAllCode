<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class LeadValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "lType" => 'required|in:Material Lead,Dealership Lead',
                "lFullName" => "nullable|max:255",
                "lCompanyName" => "nullable|max:255",
                "lShopName" => "nullable|max:255",
                "lMobileNumber" => "nullable|digits:10",
                "lEmail" => "nullable|email",
                "dRegionId" => "nullable|max:255",
                "dRName" => "nullable|max:255",
                "pAddress" => "nullable|max:255",
                "platitude" => "nullable|max:255",
                "plongitude" => "nullable|max:255",
                "pStateId" => "nullable|max:255",
                "pSName" => "nullable|max:255",
                "pCityId" => "nullable|max:255",
                "pCName" => "nullable|max:255",
                "pTalukaId" => "nullable|max:255",
                "pTName" => "nullable|max:255",
                "pPincode" => "nullable|max:255",
                "cAddress" => "nullable|max:255",
                "clatitude" => "nullable|max:255",
                "clongitude" => "nullable|max:255",
                "cStateId" => "nullable|max:255",
                "cSName" => "nullable|max:255",
                "cCityId" => "nullable|max:255",
                "cCName" => "nullable|max:255",
                "cTalukaId" => "nullable|max:255",
                "cTName" => 'nullable|max:255',
                "cPincode" => 'nullable|max:255',
                "projectName" => "nullable|max:255",
                "totalQTMT" => "nullable|max:255",
                "dateOfDelivery" => "nullable|date_format:Y-m-d",
                "firmRegistrationNumber" => "nullable|max:255",
                "actLicenceNumber" => "nullable|max:255",
                "gstTinNumber" => "nullable|max:255",
                "panImage" => "nullable|max:255",
                "firmType" => "nullable|max:255",
                "CIN" => 'nullable|max:255',
                "shopWarehouseArea" => 'nullable|max:255',
                "modeOfPayment" => 'nullable|max:255',
                "attachmentImage" => 'nullable|max:255',
                "descriptionStore" => 'nullable',
                "spaceAvailable" => 'nullable|max:255',
                "budget" => 'nullable|max:255',
            ];

            // if($request->fType == 'Dealer')
            // {
            //     $result['dType'] = 'required|in:Main Dealer,Sub Dealer';
            //     $result['photo'] = 'required';
            //     $result['shopPhoto'] = 'required';
            // }else{
            //     $result['date'] = 'required|date_format:Y-m-d';
            //     $result['time'] = 'required|date_format:H:i';
            // }

            return $result;
    }
}
