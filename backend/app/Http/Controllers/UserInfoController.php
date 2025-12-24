<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserInfoServiceInterface;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserInfoController extends Controller
{
    public function __construct(
        private readonly UserInfoServiceInterface $userInfoService
    ) {}

    /**
     * Получить информацию о текущем пользователе
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();
        $user->load('userInfo');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'telegram_username' => $user->telegram_username,
                    'info' => $user->userInfo ? [
                        'height' => $user->userInfo->height,
                        'weight' => $user->userInfo->weight,
                        'age' => $user->userInfo->age,
                    ] : null
                ],

            ]
        ]);
    }

    /**
     * Обновить или создать информацию о пользователе
     */
    public function update(UpdateUserInfoRequest $request): JsonResponse
    {
        $user = auth()->user();
        $userInfo = $user->userInfo()->updateOrCreate(
            [], // условие поиска (пустое, так как связь один к одному)
            $request->validated()
        );

        return response()->json([
            'message' => 'User info updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'telegram_username' => $user->telegram_username,
                ],
                'info' => [
                    'height' => $userInfo->height,
                    'weight' => $userInfo->weight,
                    'age' => $userInfo->age,
                ]
            ]
        ]);
    }

    /**
     * Сохранить или обновить информацию о пользователе
     */
    public function store(StoreUserInfoRequest $request, User $user): JsonResponse
    {
        $userInfo = $this->userInfoService->saveUserInfo($user, $request->validated());

        return response()->json([
            'message' => 'User info saved successfully',
            'data' => $userInfo
        ]);
    }
}