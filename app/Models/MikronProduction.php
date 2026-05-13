<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MikronProduction extends Model
{
    protected $table = 'mikronproduction';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'deleted', 'title', 'parent', 'level', 'count', 'text', 'image',
        'links', 'order', 'top', 'normal_height', 'small_height', 'pdf',
        'buttonpaddn', 'buttonpadds', 'tops',
    ];

    /**
     * Дочерние категории
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent', 'id')
            ->active()
            ->orderBy('order')
            ->orderBy('title');
    }

    /**
     * Родительская категория
     */
    public function parentRow(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    /**
     * Только активные (не удалённые)
     */
    public function scopeActive($query)
    {
        return $query->where('deleted', 0);
    }
}