<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;

class CmsValidation extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
            return [
                "slug" => 'required|in:term-condition,privacy-policy,hr-policy',
            ];

    }
}
