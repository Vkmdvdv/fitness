<?php

namespace App\Tasks;

use App\DTO\ExerciseFiltersDTO;

class ExerciseFilterTask
{
    /**
     * Обрабатывает фильтры для упражнений и возвращает DTO
     * 
     * @param array $filters Исходные фильтры
     * @return ExerciseFiltersDTO
     */
    public function run(array $filters): ExerciseFiltersDTO
    {
        return new ExerciseFiltersDTO(
            muscleGroups: $this->processStringToIntArray($filters['muscle_groups'] ?? null),
            equipment: $this->processStringToIntArray($filters['equipment'] ?? null),
            difficultyLevels: $this->processStringToIntArray($filters['difficulty_levels'] ?? null),
            search: $filters['search'] ?? null
        );
    }

    /**
     * Преобразует строку с разделителями-запятыми в массив целых чисел
     *
     * @param string|array|null $value
     * @return array|null
     */
    private function processStringToIntArray(string|array|null $value): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return array_map('intval', $value);
        }

        if (is_string($value) && strpos($value, ',') !== false) {
            return array_map('intval', explode(',', $value));
        }

        if (is_string($value) && !empty($value)) {
            return [intval($value)];
        }

        return null;
    }
} 