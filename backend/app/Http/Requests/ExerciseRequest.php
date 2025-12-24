<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_ru' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'difficulty_level_id' => 'required|exists:difficulty_levels,id',
            'video_url' => 'nullable|url|max:255',
            'image_url' => 'nullable|url|max:255',
            'muscle_groups' => 'required|array|min:1',
            'muscle_groups.*.id' => 'required|exists:muscle_groups,id',
            'muscle_groups.*.type' => 'required|in:primary,secondary',
            'equipment' => 'nullable|array',
            'equipment.*.id' => 'required|exists:equipment,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Exercise name is required',
            'muscle_groups.required' => 'At least one muscle group is required',
            'muscle_groups.min' => 'At least one muscle group is required',
            'muscle_groups.*.id.exists' => 'Selected muscle group does not exist',
            'muscle_groups.*.type.in' => 'Muscle group type must be primary or secondary',
            'equipment.*.id.exists' => 'Selected equipment does not exist',
            'difficulty_level_id.required' => 'Difficulty level is required',
            'difficulty_level_id.exists' => 'Selected difficulty level does not exist',
        ];
    }
}