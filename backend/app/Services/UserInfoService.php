<?php

namespace App\Services;

use App\Contracts\Services\UserInfoServiceInterface;
use App\Models\User;
use App\Models\UserInfo;
use App\DTO\UserInfoDTO;

class UserInfoService implements UserInfoServiceInterface
{
    public function getUserInfo(User $user): ?UserInfo
    {
        return $user->userInfo;
    }

    public function saveUserInfo(User $user, array $data): UserInfo
    {
        return $user->userInfo()->updateOrCreate([], $data);
    }

    public function getUserInfoDTO(User $user): ?UserInfoDTO
    {
        $userInfo = $this->getUserInfo($user);
        
        if (!$userInfo) {
            return null;
        }
        
        return UserInfoDTO::fromModel($userInfo);
    }
} 