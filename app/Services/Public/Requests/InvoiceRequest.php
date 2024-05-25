<?php

namespace App\Services\Public\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'invoice' => ['required', 'string']
        ];
    }
}
