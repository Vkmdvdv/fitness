<?php

namespace App\DTO;

use App\Models\UserInfo;

class UserInfoDTO
{
    public function __construct(
        public readonly float $height,
        public readonly float $weight,
        public readonly int $age,
        public readonly array $user
    ) {}

    public static function fromModel(UserInfo $userInfo): self
    {
        return new self(
            height: $userInfo->height,
            weight: $userInfo->weight,
            age: $userInfo->age,
            user: [
                'id' => $userInfo->user->id,
                'name' => $userInfo->user->name,
                'email' => $userInfo->user->email,
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'height' => $this->height,
            'weight' => $this->weight,
            'age' => $this->age,
            'user' => $this->user,
        ];
    }
} 