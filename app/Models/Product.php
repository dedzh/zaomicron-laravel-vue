<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'magazin';
    protected $primaryKey = 'code';      // первичный ключ – поле `code`
    public $timestamps = false;

    protected $fillable = ['name', 'parent', 'price', 'product', 'quantity']; // quantity – поле наличия (в старом коде использовалось row[6] – количество)

    // Категория, к которой принадлежит товар
    public function category()
    {
        return $this->belongsTo(Category::class, 'parent');
    }

    // Метод для получения изображения (если есть)
    public function getImageUrl()
    {
      
        return asset('img/logo.png');
    }
}