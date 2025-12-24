<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : auth()->id();
        
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'avatar' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            // Добавьте другие поля, которые можно обновлять
        ];
    }
} 