<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\TickerController;
use App\Http\Controllers\Api\FeedbackController;

Route::prefix('catalog')->group(function () {
    Route::get('categories', [CatalogController::class, 'categories']);
    Route::get('category/{id}', [CatalogController::class, 'category']);
    Route::get('product/{id}', [CatalogController::class, 'product']);
    Route::get('search', [CatalogController::class, 'search']);
});

Route::post('feedback', [FeedbackController::class, 'send']);   // обратная связь
Route::post('partner', [FeedbackController::class, 'partner']); // заявка партнёра
Route::post('subscribe', [FeedbackController::class, 'subscribe']); // подписка