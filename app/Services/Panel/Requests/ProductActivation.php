<?php

namespace App\Services\Panel\Requests;

use App\Services\Panel\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductActivation extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'products' => ['required', 'array', 'max:100'],
            'products.*.barcode' => ['required', 'string'],
            'products.*.active' => ['required', 'boolean'],
        ];
    }
}
