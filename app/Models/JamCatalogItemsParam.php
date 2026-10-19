<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamCatalogItemsParam extends Model
{
    protected $table = 'jam_catalog_items_params';
    public $timestamps = false;

    protected $fillable = ['item_id', 'param_id', 'value', 'value_int', 'value_dec'];

    public function item()
    {
        return $this->belongsTo(JamCatalogItem::class, 'item_id');
    }

    public function paramDefinition()
    {
        return $this->belongsTo(JamCatalogParam::class, 'param_id');
    }

    public function selectValue()
    {
        return $this->belongsTo(JamCatalogParamsValue::class, 'value_int', 'id');
    }
}