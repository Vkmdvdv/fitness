<?php

namespace App\Contracts\Services;

use App\Models\User;
use App\Models\UserInfo;

interface UserInfoServiceInterface
{
    public function getUserInfo(User $user): ?UserInfo;
    
    public function saveUserInfo(User $user, array $data): UserInfo;
}