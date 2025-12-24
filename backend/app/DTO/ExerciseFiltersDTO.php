<?php

namespace App\DTO;

class ExerciseFiltersDTO
{
    /**
     * @param array|null $muscleGroups
     * @param array|null $equipment
     * @param array|null $difficultyLevels
     * @param string|null $search
     */
    public function __construct(
        public readonly ?array $muscleGroups = null,
        public readonly ?array $equipment = null,
        public readonly ?array $difficultyLevels = null,
        public readonly ?string $search = null
    ) {}
}