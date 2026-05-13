<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'old_code',
        'parent_id',
        'name',
        'sort_order',
    ];

    public function scopeActive($query)
    {
        return $query;
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
                    ->orderBy('sort_order')
                    ->orderBy('name');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}