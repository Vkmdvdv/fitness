<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class DifficultyLevel extends Model
{
    protected $fillable = [
        'name',
        'name_ru',
        'description',
        'description_ru',
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
     * Получить упражнения с этим уровнем сложности
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }
} 