<?php

declare(strict_types=1);

use App\Http\Controllers\BettingController;
use App\Http\Controllers\TellerConsoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('teller')->group(function () {
    Route::get('', [TellerConsoleController::class::class, 'index']);
    Route::post('betting', [BettingController::class, 'store']);
});
