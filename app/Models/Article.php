<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'name',
        'code',
        'anons',
        'text',
        'date',
        'img_id',
        'description'
    ];

    protected $casts = [
        'date' => 'datetime',
        'active' => 'boolean',
    ];

    // Активные статьи (active=1, trash=0)
    public function scopePublished($query)
    {
        return $query->where('active', 1)->where('trash', 0);
    }

    // Связь с изображением (если есть таблица files)
    // Если нет, можно пока возвращать заглушку
    public function image() {}
    public function category() {}
}
