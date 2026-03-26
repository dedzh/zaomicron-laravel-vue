<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategory extends Model
{
    protected $table = 'jam_catalog_cats';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['parent_id', 'name', 'url', 'descr_short', 'descr', 'main_img', 'active', 'sort'];

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(CatalogCategory::class, 'parent_id');
    }

    // Дочерние категории
    public function children()
    {
        return $this->hasMany(CatalogCategory::class, 'parent_id')->orderBy('sort');
    }

    // Активные категории
    public function scopeActive($query)
    {
        return $query->where('active', 1)->where('trash', 0);
    }
}