<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'muscle_groups' => 'nullable|array',
            'muscle_groups.*' => 'integer|exists:muscle_groups,id',
            'equipment' => 'nullable|array',
            'equipment.*' => 'integer|exists:equipment,id',
            'difficulty_levels' => 'nullable|integer',
            'search' => 'nullable|string|max:255',
        ];
    }
}