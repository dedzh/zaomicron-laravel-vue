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

    // Аксессор для основного изображения
    public function getImageUrlAttribute(): ?string
    {
        $firstImage = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        if ($firstImage && !empty($firstImage->image_path)) {
            return $this->normalizeImagePath($firstImage->image_path);
        }

        return null;
    }

    private function normalizeImagePath(string $path): string
    {
        $normalized = '/' . ltrim(trim($path), '/');

        // Старые данные могут содержать удвоенный сегмент _images/_images.
        // На сервере каталог обычно монтируется как /_images/...
        $normalized = preg_replace('#^/_images/_images/#', '/_images/', $normalized);

        return $normalized;
    }
}