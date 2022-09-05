<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class MerchandisesValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "mType" => 'required|in:Gift,Order',
                "rjDealerId" => "nullable|max:36",
                "mDate" => "nullable|date_format:Y-m-d",
                "mStateId" => "nullable|max:255",
                "mSName" => "nullable|max:255",
                "mCityId" => "nullable|max:255",
                "mCName" => "nullable|max:255",
                "mTalukaId" => "nullable|max:255",
                "mTName" => "nullable|max:255",
                "mPinCode" => "nullable|max:255",
                "mPhoto" => "nullable|max:255",
            ];

            return $result;
    }
}
