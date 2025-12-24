<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Проверка авторизации будет через middleware auth:sanctum
    }

    public function rules(): array
    {
        return [
            'height' => 'numeric|between:50,250|nullable',
            'weight' => 'numeric|between:20,300|nullable',
            'age' => 'integer|between:1,150|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'height.required' => 'Height is required',
            'height.between' => 'Height must be between 50 and 250 cm',
            'weight.required' => 'Weight is required',
            'weight.between' => 'Weight must be between 20 and 300 kg',
            'age.required' => 'Age is required',
            'age.between' => 'Age must be between 1 and 150 years',
        ];
    }
} 