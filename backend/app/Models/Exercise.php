<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'description',
        'description_ru',
        'difficulty_level_id',
        'video_url',
        'image_url',
    ];

    protected $appends = ['translated_name', 'translated_description'];

    /**
     * Получить переведенное название
     */
    public function getTranslatedNameAttribute(): string
    {
        $locale = App::getLocale();
        if ($locale === 'ru' && !empty($this->name_ru)) {
            return $this->name_ru;
        }
        return $this->name;
    }

    /**
     * Получить переведенное описание
     */
    public function getTranslatedDescriptionAttribute(): ?string
    {
        $locale = App::getLocale();
        if ($locale === 'ru' && !empty($this->description_ru)) {
            return $this->description_ru;
        }
        return $this->description;
    }

    /**
     * Получить уровень сложности упражнения
     */
    public function difficultyLevel(): BelongsTo
    {
        return $this->belongsTo(DifficultyLevel::class);
    }

    /**
     * Получить группы мышц для упражнения
     */
    public function muscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_group')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Получить основные группы мышц для данного упражнения
     */
    public function primaryMuscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_group')
                    ->wherePivot('type', 'primary')
                    ->withTimestamps();
    }

    /**
     * Получить вспомогательные группы мышц для данного упражнения
     */
    public function secondaryMuscleGroups(): BelongsToMany
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_group')
                    ->wherePivot('type', 'secondary')
                    ->withTimestamps();
    }

    /**
     * Получить оборудование для упражнения
     */
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'exercise_equipment')
                    ->withTimestamps();
    }

    /**
     * Scope для фильтрации по группе мышц
     */
    public function scopeByMuscleGroup(Builder $query, $muscleGroupId): Builder
    {
        return $query->whereHas('muscleGroups', function ($query) use ($muscleGroupId) {
            $query->whereIn('muscle_groups.id', (array) $muscleGroupId);
        });
    }

    /**
     * Scope для фильтрации по оборудованию
     */
    public function scopeByEquipment(Builder $query, $equipmentId): Builder
    {
        return $query->whereHas('equipment', function ($query) use ($equipmentId) {
            $query->whereIn('equipment.id', (array) $equipmentId);
        });
    }

    /**
     * Scope для фильтрации по уровню сложности
     */
    public function scopeByDifficultyLevel(Builder $query, $difficultyLevelId): Builder
    {
        return $query->whereIn('difficulty_level_id', (array) $difficultyLevelId);
    }
}