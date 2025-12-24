<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // или добавьте здесь свою логику авторизации
    }

    public function rules(): array
    {
        return [
            'height' => 'required|numeric|between:50,250',
            'weight' => 'required|numeric|between:20,300',
            'age' => 'required|integer|between:1,150',
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