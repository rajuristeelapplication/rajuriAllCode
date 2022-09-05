<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
   /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
      //  $errors = collect((new ValidationException($validator))->errors())->first();
       $errors = (new ValidationException($validator))->errors();
        $message = '';

        if (!empty(current($errors)[0])) {
            $message = current($errors)[0];
        }

        throw new HttpResponseException(
            response()->json([
                'status' => 0,
                'result' => new \stdClass(),
                'message' => $message,
            ], 200)
        );
    }
}
