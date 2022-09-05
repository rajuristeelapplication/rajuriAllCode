<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class DealerValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "fType" => 'required|in:Dealer,Engineer,Architect,Mason,Contractor,Construction Firm',
                "name" => "required|max:255",
                "dealerId" => "required|max:255",
                "name" => "required|max:255",
                "firmName" => "required|max:255",
                "location" => "required|max:255",
                "latitude" => 'required|between:-90,90',
                "longitude" => 'required|between:-180,180',
                "address1" => "required|max:255",
                "address2" => "required|max:255",
                "stateId" => "required|max:36",
                "sName" => "required|max:255",
                "cityId" => "required|max:36",
                "cName" => "required|max:255",
                "talukaId" => "required|max:36",
                "tName" => "required|max:255",
                "pinCode" => "required|digits:6",
                "regionId" => "required|max:36",
                "rName" => "required|max:255",
                "wpMobileNumber" => "required|digits:10",
                "mobileNumber" => "nullable|digits:10",
                "email" => "nullable|email",
                // "dob" => "required|date_format:Y-m-d",
                "yearOfIncorporation" => "nullable|max:3",
                "aadharNo" => "nullable|numeric|digits:12",
                "msRequriedMaterial" => "nullable|max:255",
                "msUtillNow" => "nullable|max:255",
                "msCompletedProject" => "nullable|max:255",
                "msOnGoingProject" => "nullable|max:255",
                "msYearOfIncorporation" => "nullable|max:3",
                "piTypeOfSite" => "nullable|max:255",
                "piStatusOfSite" => "nullable|max:255",
                "piArea" => "nullable|max:255",
                "piEstimateCost" => "nullable|regex:/^\d+(\.\d{1,2})?$/",
                "piProjectEngineer" => "nullable|max:255",
                "piArchitect" => "nullable|max:255",
                "piExecutor" => "nullable|max:255",
                "cdFirmRegistrationNumber" => "nullable|max:255",
                "cdShopActLicenceNumber" => "nullable|max:255",
                "cdGstNumber" => "nullable|max:255",
                "cdPan" => "nullable|max:255",
                "cdFirmType" => "nullable|max:255",
                "cdCin" => "nullable|max:255",
                "cdShopWarehouseArea" => "nullable|max:255",
                "bdModeOfPayment" => "nullable|max:255",
                "bdBankName" => "nullable|max:255",
                "bdBankAddress" => "nullable|max:255",
                "bdAccountNumber" => "nullable|max:255",
                "bdIfscCode" => "nullable|max:255",
                "bdNatureOfAccount" => "nullable|max:255",
                "dealerStatus" => 'required|in:Create,Delete,Update',
            ];

            if($request->fType == 'Dealer')
            {
                $result['dType'] = 'required|in:Main Dealer,Sub Dealer';
                $result['photo'] = 'required';
                $result['shopPhoto'] = 'required';
                $result['dob'] = 'required|date_format:Y-m-d';
            }else{
                $result['date'] = 'required|date_format:Y-m-d';
                $result['time'] = 'required|date_format:H:i';
            }

            return $result;
    }
}
