<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogCategory extends Model
{
    protected $table = 'CHEESECAKE_CATS_TREE';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // Дамп cheesecake-cаты: ID, PARENT_ID, NAME
    protected $fillable = ['PARENT_ID', 'NAME'];

    // На чтение это не влияет, но избегаем неверных предположений об auto-increment.
    public $incrementing = false;
    protected $keyType = 'int';

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(CatalogCategory::class, 'PARENT_ID', 'ID');
    }

    // Дочерние категории
    public function children()
    {
        return $this->hasMany(CatalogCategory::class, 'PARENT_ID', 'ID')->orderBy('NAME');
    }
    // CatalogController.php
public function categories()
{
    $categories = CatalogCategory::active()
        ->whereNull('PARENT_ID')
        ->with('children')
        ->orderBy('NAME')
        ->get();

    return response()->json($categories->map(fn ($cat) => $this->mapCategoryNode($cat)));
}

private function mapCategoryNode(CatalogCategory $cat): array
{
    return [
        'id' => $cat->ID,
        'name' => $cat->NAME,
        'title' => $cat->NAME,
        'children' => $cat->children->map(fn ($ch) => $this->mapCategoryNode($ch))->values()->all(),
        // можно добавить иконку, если есть поле в БД
        'icon' => null,
    ];
}


    // Активные категории
    public function scopeActive($query)
    {
        // В cheesecake-данных нет полей актив/удалено, поэтому возвращаем как есть.
        return $query;
    }
}