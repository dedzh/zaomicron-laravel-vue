<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    protected $table = 'jam_catalog_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['pat_id', 'name', 'article', 'model', 'descr_1', 'descr_2', 'descr_3', 'price_1', 'price_2', 'in_stock', 'stock_quantity', 'main_img', 'url'];

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'pat_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->where('trash', 0);
    }
}