<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazin extends Model
{
    protected $table = 'magazin';
    protected $primaryKey = 'code';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'code', 'parent', 'name', 'measure', 'price', 'col1', 'col2',
        'sale', 'product', 'nom_weight', 'brand'
    ];

    /**
     * Связь с категорией (mikronproduction.id = magazin.parent)
     */
public function category()
{
    return $this->belongsTo(MikronProduction::class, 'parent', 'id');
}
    public function scopeActive($query)
    {
        return $query;
    }

    /**
     * Остаток на складе
     */
    public function getStockAttribute()
    {
        return max(0, (int) $this->col1) + max(0, (int) $this->col2);
    }
}