<?php

declare(strict_types=1);

use App\Http\Controllers\BettingController;
use App\Http\Controllers\RecentBetController;
use App\Http\Controllers\TellerConsoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);

Route::prefix('teller')->group(function () {
    Route::get('console', [TellerConsoleController::class, 'index']);
    Route::get('recent-bet/{event}', [RecentBetController::class, 'show']);
    Route::get('bet-history', [BettingController::class, 'index']);
    Route::post('betting', [BettingController::class, 'store']);
})->middleware(['auth:sanctum', 'teller']);
