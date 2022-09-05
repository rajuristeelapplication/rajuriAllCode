<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class DealerValidationGet extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result = [];

            if(!empty($request->fType))
            {
                $result['fType'] = 'required|array|min:1|in:Dealer,Engineer,Architect,Mason,Contractor,Construction Firm';
            }
            if(!empty($request->sort))
            {
                $result['sort'] = 'required|in:asc,desc,Main Dealer,Sub Dealer';
            }


            if(!empty($request->dType))
            {
                $result['dType'] = 'required|in:Main Dealer,Sub Dealer';
            }


            // if($request->fType == 'Dealer')
            // {
            //     $result['dType'] = 'required|in:Main Dealer,Sub Dealer';
            // }

            return $result;

    }
}
