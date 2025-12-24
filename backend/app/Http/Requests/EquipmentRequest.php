<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:equipment,name,' . $this->equipment?->id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Equipment name is required',
            'name.unique' => 'This equipment already exists',
        ];
    }
}