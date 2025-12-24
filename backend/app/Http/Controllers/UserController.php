<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Обновление данных пользователя
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        // Проверяем, имеет ли текущий пользователь право обновлять данного пользователя
        $this->authorize('update', $user);
        
        $user->update($request->validated());
        
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
    
    /**
     * Обновление данных текущего пользователя
     *
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function updateCurrentUser(UpdateUserRequest $request): JsonResponse
    {
        $user = auth()->user();
        
        $user->update($request->validated());
        
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
} 