<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class ScheduleValidationGet extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $result =   [
               "sType" => 'required|in:upcomming,history,todayVisit',
            ];
            return $result;
    }
}
