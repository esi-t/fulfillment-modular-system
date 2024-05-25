<?php

namespace App\Services\Contractors\Base\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;

trait HasValidationError
{
    protected function failedValidation($validator)
    {
        $response = response()->json([
            'success' => false,
            'errors' => $validator->errors(),
            'message' => 'Validation errors occurred'
        ], 422);

        throw new HttpResponseException($response);
    }
}
