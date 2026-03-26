<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'mikronproduction';
    protected $primaryKey = 'id';
    public $timestamps = false; // в таблице нет created_at/updated_at

    protected $fillable = ['parent', 'level', 'title', 'text', 'image'];

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent');
    }

    // Дочерние категории
    public function children()
    {
        return $this->hasMany(Category::class, 'parent');
    }

    // Товары, относящиеся к этой категории (связь через magazin.parent)
    public function products()
    {
        return $this->hasMany(Product::class, 'parent', 'id');
    }
}