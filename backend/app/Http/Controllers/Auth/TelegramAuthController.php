<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TelegramAuthController extends Controller
{
    public function auth(Request $request): JsonResponse
    {
        $request->validate([
            'telegram_id' => 'required',
            'first_name' => 'required|string',
            'username' => 'nullable|string',
            'auth_date' => 'required|integer',
            'hash' => 'required|string',
        ]);

        //TEST ONLY
        if (false && !$this->checkTelegramAuthorization($request->all())) {            
            return response()->json(['message' => 'Invalid hash'], 400);
        }

        // Сначала пробуем найти пользователя по telegram_id или email
        $user = User::where('telegram_id', $request->id)
            ->orWhere('email', $request->id . '@telegram.com')
            ->first();

        // Если пользователь не найден, создаем нового
        if (!$user) {
            $user = User::create([
                'telegram_id' => $request->telegram_id,
                'name' => $request->first_name,
                'telegram_username' => $request->username,
                'email' => $request->id . '@telegram.com',
                'password' => Hash::make(Str::random(16)),
            ]);
        } else {
            // Обновляем существующего пользователя
            $user->update([
                'telegram_id' => $request->id,
                'name' => $request->first_name,
                'telegram_username' => $request->username,
            ]);
        }
        
        // Загружаем связанную информацию о пользователе
        $user->load('userInfo');

        $token = $user->createToken('telegram-auth')->plainTextToken;

        return response()->json([
            'token' => $token,
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
            ]
        ]);
    }

    private function checkTelegramAuthorization(array $auth_data): bool
    {
        $bot_token = config('services.telegram.bot_token');
        
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        
        $data_check_arr = [];
        
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        
        return $check_hash === $hash;
    }
} 