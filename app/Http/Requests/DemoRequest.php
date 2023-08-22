<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DemoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Set to false if you need authorization
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'required|array',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response: response(content: $validator->errors(), status: 422));
    }
}
