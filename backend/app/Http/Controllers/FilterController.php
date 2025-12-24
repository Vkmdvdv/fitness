<?php

namespace App\Http\Controllers;

use App\Models\MuscleGroup;
use App\Models\Equipment;
use App\Models\DifficultyLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

class FilterController extends Controller
{
    /**
     * Получить все доступные фильтры для упражнений
     */
    public function index(): JsonResponse
    {
        // Получаем все группы мышц
        $muscleGroups = MuscleGroup::orderBy('name')->get()->map(function ($muscleGroup) {
            return [
                'id' => $muscleGroup->id,
                'name' => $muscleGroup->translated_name,
                'description' => $muscleGroup->translated_description,
                'image_url' => $muscleGroup->image_url,
            ];
        });
        
        // Получаем все оборудование
        $equipment = Equipment::orderBy('name')->get()->map(function ($equipment) {
            return [
                'id' => $equipment->id,
                'name' => $equipment->translated_name,
                'description' => $equipment->translated_description,
                'image_url' => $equipment->image_url,
            ];
        });
        
        // Получаем все уровни сложности
        $difficultyLevels = DifficultyLevel::orderBy('id')->get()->map(function ($level) {
            return [
                'id' => $level->id,
                'name' => $level->translated_name,
                'description' => $level->translated_description,
            ];
        });
        
        return response()->json([
            'data' => [
                'muscle_groups' => $muscleGroups,
                'equipment' => $equipment,
                'difficulty_levels' => $difficultyLevels,
            ]
        ]);
    }
    
    /**
     * Получить все группы мышц
     */
    public function muscleGroups(): JsonResponse
    {
        $muscleGroups = MuscleGroup::orderBy('name')->get()->map(function ($muscleGroup) {
            return [
                'id' => $muscleGroup->id,
                'name' => $muscleGroup->translated_name,
                'description' => $muscleGroup->translated_description,
                'image_url' => $muscleGroup->image_url,
            ];
        });
        
        return response()->json([
            'data' => $muscleGroups
        ]);
    }
    
    /**
     * Получить все оборудование
     */
    public function equipment(): JsonResponse
    {
        $equipment = Equipment::orderBy('name')->get()->map(function ($equipment) {
            return [
                'id' => $equipment->id,
                'name' => $equipment->translated_name,
                'description' => $equipment->translated_description,
                'image_url' => $equipment->image_url,
            ];
        });
        
        return response()->json([
            'data' => $equipment
        ]);
    }
    
    /**
     * Получить все уровни сложности
     */
    public function difficultyLevels(): JsonResponse
    {
        $difficultyLevels = DifficultyLevel::orderBy('id')->get()->map(function ($level) {
            return [
                'id' => $level->id,
                'name' => $level->translated_name,
                'description' => $level->translated_description,
            ];
        });
        
        return response()->json([
            'data' => $difficultyLevels
        ]);
    }
} 