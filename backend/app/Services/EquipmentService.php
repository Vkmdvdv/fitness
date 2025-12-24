<?php

namespace App\Services;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Collection;

class EquipmentService
{
    public function getAllEquipment(): Collection
    {
        return Equipment::orderBy('name')->get();
    }

    public function createEquipment(array $data): Equipment
    {
        return Equipment::create($data);
    }

    public function updateEquipment(Equipment $equipment, array $data): Equipment
    {
        $equipment->update($data);
        return $equipment;
    }

    public function deleteEquipment(Equipment $equipment): bool
    {
        return $equipment->delete();
    }
}