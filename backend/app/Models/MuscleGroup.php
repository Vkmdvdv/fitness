<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;

class MuscleGroup extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'description',
        'description_ru',
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
     * Получить упражнения для данной группы мышц
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_group')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Получить упражнения, где эта группа мышц является основной
     */
    public function primaryExercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_group')
                    ->wherePivot('type', 'primary')
                    ->withTimestamps();
    }

    /**
     * Получить упражнения, где эта группа мышц является вспомогательной
     */
    public function secondaryExercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle_group')
                    ->wherePivot('type', 'secondary')
                    ->withTimestamps();
    }
} 