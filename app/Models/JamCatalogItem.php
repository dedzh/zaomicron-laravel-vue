<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamCatalogItem extends Model
{
    protected $table = 'jam_catalog_items';
    public $timestamps = false;

    public function params()
    {
        return $this->hasMany(JamCatalogItemsParam::class, 'item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'article', 'old_code');
    }
}