<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogItem extends Model
{
    protected $table = 'FROM_CHEESECAKE_TO_SITE';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Дамп cheesecake-товаров: ID, NAME, GROUP_ID, PRICE, BRAND, REST_FREE и т.д.
    protected $fillable = ['GROUP_ID', 'NAME', 'PRICE', 'BRAND', 'REST_FREE', 'REST_FACT', 'REST_OWN_FREE'];

    public $incrementing = false;
    protected $keyType = 'int';

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'GROUP_ID', 'ID');
    }

    public function scopeActive($query)
    {
        // В cheesecake-данных нет полей актив/удалено, поэтому возвращаем как есть.
        return $query;
    }
}