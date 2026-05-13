<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\FeedbackController;

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog/filters',      [CatalogController::class, 'filters']);
Route::get('/catalog/categories',   [CatalogController::class, 'categories']);
Route::get('/catalog/browse/{id?}', [CatalogController::class, 'browse']);
Route::get('/catalog/products',     [CatalogController::class, 'products']);
Route::get('/catalog/featured',     [CatalogController::class, 'featured']);
Route::get('/catalog/search',       [CatalogController::class, 'search']);
Route::get('/catalog/favorites',    [CatalogController::class, 'favoritesProducts']);
Route::get('/catalog/category/{id}', [CatalogController::class, 'category']);
Route::get('/catalog/product/{id}', [CatalogController::class, 'product']);

Route::get('/main/promo',           [CatalogController::class, 'mainPromo']);

Route::post('/feedback', [FeedbackController::class, 'send']);
Route::post('/partner', [FeedbackController::class, 'partner']);
Route::post('/subscribe', [FeedbackController::class, 'subscribe']);


Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{code}', [ArticleController::class, 'show']);

Route::get('/news', function () {
    return response()->json([]);
});
