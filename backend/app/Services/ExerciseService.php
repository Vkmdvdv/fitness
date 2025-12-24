<?php

namespace App\Services;

use App\Models\Exercise;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\DTO\ExerciseFiltersDTO;

class ExerciseService
{
    public function getExercises(ExerciseFiltersDTO $filters): LengthAwarePaginator
    {
        // Используем свойства DTO для фильтрации
        $query = Exercise::query();
        
        if ($filters->muscleGroups !== null) {
            $query->whereHas('muscleGroups', function ($q) use ($filters) {
                $q->whereIn('muscle_groups.id', $filters->muscleGroups);
            });
        }
        
        if ($filters->equipment !== null) {
            $query->whereHas('equipment', function ($q) use ($filters) {
                $q->whereIn('equipment.id', $filters->equipment);
            });
        }
        
        if ($filters->difficultyLevels !== null) {
            $query->whereIn('difficulty_level_id', $filters->difficultyLevels);
        }
        
        if ($filters->search !== null) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'ilike', "%{$filters->search}%")
                  ->orWhere('name_ru', 'ilike', "%{$filters->search}%")
                  ->orWhere('description', 'ilike', "%{$filters->search}%")
                  ->orWhere('description_ru', 'ilike', "%{$filters->search}%");
            });
        }
        
        return $query->with(['muscleGroups', 'equipment', 'difficultyLevel'])
                    ->paginate(12);
    }

    public function createExercise(array $data): Exercise
    {
        return DB::transaction(function () use ($data) {
            // Создаем упражнение
            $exercise = Exercise::create([
                'name' => $data['name'],
                'name_ru' => $data['name_ru'] ?? null,
                'description' => $data['description'] ?? null,
                'description_ru' => $data['description_ru'] ?? null,
                'difficulty_level_id' => $data['difficulty_level_id'],
                'video_url' => $data['video_url'] ?? null,
                'image_url' => $data['image_url'] ?? null,
            ]);

            // Связываем с группами мышц
            if (isset($data['muscle_groups'])) {
                $this->syncMuscleGroups($exercise, $data['muscle_groups']);
            }

            // Связываем с оборудованием
            if (isset($data['equipment'])) {
                $this->syncEquipment($exercise, $data['equipment']);
            }

            // Загружаем связанные данные
            $exercise->load(['muscleGroups', 'equipment', 'difficultyLevel']);

            return $exercise;
        });
    }

    /**
     * Обновить существующее упражнение
     */
    public function updateExercise(Exercise $exercise, array $data): Exercise
    {
        return DB::transaction(function () use ($exercise, $data) {
            // Обновляем упражнение
            $exercise->update([
                'name' => $data['name'],
                'name_ru' => $data['name_ru'] ?? $exercise->name_ru,
                'description' => $data['description'] ?? $exercise->description,
                'description_ru' => $data['description_ru'] ?? $exercise->description_ru,
                'difficulty_level_id' => $data['difficulty_level_id'],
                'video_url' => $data['video_url'] ?? $exercise->video_url,
                'image_url' => $data['image_url'] ?? $exercise->image_url,
            ]);

            // Обновляем связи с группами мышц
            if (isset($data['muscle_groups'])) {
                $this->syncMuscleGroups($exercise, $data['muscle_groups']);
            }

            // Обновляем связи с оборудованием
            if (isset($data['equipment'])) {
                $this->syncEquipment($exercise, $data['equipment']);
            }

            // Загружаем связанные данные
            $exercise->load(['muscleGroups', 'equipment', 'difficultyLevel']);

            return $exercise;
        });
    }

    /**
     * Удалить упражнение
     */
    public function deleteExercise(Exercise $exercise): bool
    {
        return $exercise->delete();
    }

    /**
     * Синхронизировать группы мышц для упражнения
     */
    private function syncMuscleGroups(Exercise $exercise, array $muscleGroups): void
    {
        $syncData = [];

        foreach ($muscleGroups as $muscleGroup) {
            $syncData[$muscleGroup['id']] = [
                'type' => $muscleGroup['type'] ?? 'primary'
            ];
        }

        $exercise->muscleGroups()->sync($syncData);
    }

    /**
     * Синхронизировать оборудование для упражнения
     */
    private function syncEquipment(Exercise $exercise, array $equipment): void
    {
        $equipmentIds = collect($equipment)->pluck('id')->toArray();
        $exercise->equipment()->sync($equipmentIds);
    }
}