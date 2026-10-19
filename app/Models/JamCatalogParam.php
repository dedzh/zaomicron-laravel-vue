<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamCatalogParam extends Model
{
    protected $table = 'jam_catalog_params';
    public $timestamps = false;

    protected $fillable = ['name', 'code', 'type', 'show_filter'];

    public function values()
    {
        return $this->hasMany(JamCatalogParamsValue::class, 'param_id');
    }
}
