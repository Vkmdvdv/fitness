<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;

class EquipmentController extends Controller
{
    public function __construct(
        private readonly EquipmentService $equipmentService
    ) {}

    public function index(): JsonResponse
    {
        $equipment = $this->equipmentService->getAllEquipment();

        return response()->json([
            'data' => $equipment
        ]);
    }

    public function store(EquipmentRequest $request): JsonResponse
    {
        $equipment = $this->equipmentService->createEquipment($request->validated());

        return response()->json([
            'message' => 'Equipment created successfully',
            'data' => $equipment
        ]);
    }

    public function show(Equipment $equipment): JsonResponse
    {
        return response()->json([
            'data' => $equipment
        ]);
    }

    public function update(EquipmentRequest $request, Equipment $equipment): JsonResponse
    {
        $equipment = $this->equipmentService->updateEquipment(
            $equipment,
            $request->validated()
        );

        return response()->json([
            'message' => 'Equipment updated successfully',
            'data' => $equipment
        ]);
    }

    public function destroy(Equipment $equipment): JsonResponse
    {
        $this->equipmentService->deleteEquipment($equipment);

        return response()->json([
            'message' => 'Equipment deleted successfully'
        ]);
    }
}