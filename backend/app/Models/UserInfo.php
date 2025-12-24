<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'user_info';

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'age',
    ];

    /**
     * Атрибуты, которые должны быть приведены к типам.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'age' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Получить пользователя, которому принадлежит информация.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}