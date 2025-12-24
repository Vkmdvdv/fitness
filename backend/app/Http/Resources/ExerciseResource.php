<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated_name,
            'name_ru' => $this->name_ru,
            'name_en' => $this->name,
            'description' => $this->translated_description,
            'description_ru' => $this->description_ru,
            'description_en' => $this->description,
            'difficulty_level' => new DifficultyLevelResource($this->whenLoaded('difficultyLevel')),
            'difficulty_level_id' => $this->difficulty_level_id,
            'muscle_groups' => MuscleGroupResource::collection($this->whenLoaded('muscleGroups')),
            'equipment' => EquipmentResource::collection($this->whenLoaded('equipment')),
            'video_url' => $this->video_url,
            'image_url' => $this->image_url,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

