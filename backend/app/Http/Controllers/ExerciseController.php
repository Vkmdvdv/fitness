<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseRequest;
use App\Http\Requests\ExerciseFilterRequest;
use App\Models\Exercise;
use App\Services\ExerciseService;
use App\Tasks\ExerciseFilterTask;
use Illuminate\Http\JsonResponse;

class ExerciseController extends Controller
{
    public function __construct(
        private readonly ExerciseService $exerciseService
    ) {}

    /**
     * Получить список упражнений с возможностью фильтрации
     */
    public function index(ExerciseFilterRequest $request): JsonResponse
    {
        $filtersDTO = app(ExerciseFilterTask::class)->run($request->validated());

        $exercises = $this->exerciseService->getExercises($filtersDTO);

        return response()->json([
            'data' => $exercises->items(),
            'meta' => [
                'current_page' => $exercises->currentPage(),
                'last_page' => $exercises->lastPage(),
                'per_page' => $exercises->perPage(),
                'total' => $exercises->total(),
            ]
        ]);
    }

    public function store(ExerciseRequest $request): JsonResponse
    {
        $exercise = $this->exerciseService->createExercise($request->validated());

        return response()->json([
            'message' => __('messages.exercise_created'),
            'data' => $exercise
        ]);
    }

    public function show(Exercise $exercise): JsonResponse
    {
        $exercise->load(['muscleGroups', 'equipment','difficultyLevel']);

        return response()->json($exercise);
    }

    public function update(ExerciseRequest $request, Exercise $exercise): JsonResponse
    {
        $exercise = $this->exerciseService->updateExercise(
            $exercise,
            $request->validated()
        );

        return response()->json([
            'message' => __('messages.exercise_updated'),
            'data' => $exercise
        ]);
    }

    public function destroy(Exercise $exercise): JsonResponse
    {
        $this->exerciseService->deleteExercise($exercise);

        return response()->json([
            'message' => __('messages.exercise_deleted')
        ]);
    }
}