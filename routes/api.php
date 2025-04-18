<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ListController;
use App\Http\Controllers\Api\V1\HistoryController;
use App\Http\Controllers\Api\V1\ReviewController;

Route::prefix('v1')->group(function () {

    Route::get('lists/names', [ListController::class, 'names'])
        ->name('api.lists.names');

    Route::get('lists', [ListController::class, 'snapshot'])
        ->name('api.lists.snapshot');

    Route::get('history', [HistoryController::class, 'search'])
        ->name('api.history.search');

    Route::get('reviews', [ReviewController::class, 'search'])
        ->name('api.reviews.search');
});
