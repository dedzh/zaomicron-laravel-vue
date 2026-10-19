<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamCatalogParamsValue extends Model
{
    protected $table = 'jam_catalog_params_values';
    public $timestamps = false;

    protected $fillable = ['param_id', 'value'];

    public function param()
    {
        return $this->belongsTo(JamCatalogParam::class, 'param_id');
    }
}