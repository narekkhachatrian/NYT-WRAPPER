<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ListController;
use App\Http\Controllers\Api\V1\HistoryController;
use App\Http\Controllers\Api\V1\ReviewController;

Route::prefix('v1')->group(function () {
    Route::get('lists/names', [ListController::class, 'names']);
    Route::get('lists', [ListController::class, 'snapshot']);
    Route::get('history', [HistoryController::class, 'search']);
    Route::get('reviews', [ReviewController::class, 'search']);
});
