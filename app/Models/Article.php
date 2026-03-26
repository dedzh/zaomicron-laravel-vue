<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
   protected $table ='article';
   public $timestamps = false;

   protected $fillable = ['name', 'code', 'anons', 'text', 'date', 'img_id', 'active'];
}
