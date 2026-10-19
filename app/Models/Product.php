<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;

    protected $fillable = [
        'old_code',
        'category_id',
        'name',
        'unit_id',
        'brand_id',
        'price',
        'weight',
        'is_active',
    ];

    protected $appends = [
        'image_url',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function getImageUrlAttribute(): ?string
    {
        $firstImage = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        if (!$firstImage || !$firstImage->image_path) {
            return null;
        }

        $path = str_replace('\\', '/', trim((string) $firstImage->image_path));
        return '/' . ltrim($path, '/');
    }
    
public function jamItem()
{
    return $this->hasOne(\App\Models\JamCatalogItem::class, 'article', 'old_code');
}
}