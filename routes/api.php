<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog/filters', [CatalogController::class, 'filters']);
Route::get('/catalog/categories', [CatalogController::class, 'categories']);
Route::get('/catalog/category/{id}', [CatalogController::class, 'category']);
Route::get('/catalog/product/{id}', [CatalogController::class, 'product']);
Route::get('/catalog/featured', [CatalogController::class, 'featured']);
Route::get('/catalog/search', [CatalogController::class, 'search']);
Route::get('/main/promo', [CatalogController::class, 'mainPromo']);

Route::post('/order', [OrderController::class, 'store']);
Route::post('/feedback', [FeedbackController::class, 'send']);
Route::post('/partner', [FeedbackController::class, 'partner']);
Route::post('/subscribe', [FeedbackController::class, 'subscribe']);

// Временная заглушка для новостей
Route::get('/news', function () {
    return response()->json([]);
});
