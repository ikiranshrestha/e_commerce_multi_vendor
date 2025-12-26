<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true; // default allow — override in child if needed
    }

    /**
     * Override failed validation to return clean API JSON responses.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }

    /**
     * A helper to access validated data safely.
     */
    public function safeData()
    {
        return $this->validated();
    }
}
