<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ReimbursementValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
                "expenseId" => 'required|max:36',
                "rName" => "nullable|max:255",
                "dateofTravelling" => "nullable|date_format:Y-m-d",
                "totalAmount" => "nullable|max:255",
                "rAttachment" => "nullable|max:255",
                "fuel" => "nullable|max:255",
                "projectName" => "nullable|max:255",
                "Location" => "nullable|max:255",

            ];
            return $result;
    }
}
