<?php

declare(strict_types=1);

use App\Http\Controllers\BettingController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\GenerateManualRefController;
use App\Http\Controllers\RecentBetController;
use App\Http\Controllers\TellerConsoleController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Route::get('/user', function (Request $request) {
    $user = $request->user();

    return [
        'id' => $user->uuid,
        'username' => $user->username,
    ];
})->middleware('auth:sanctum');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('teller')->group(function () {
    Route::get('wallet', [WalletController::class, 'index'])->middleware(['auth:sanctum', 'teller']);
    Route::get('console', [TellerConsoleController::class, 'index'])->middleware(['auth:sanctum', 'teller']);
    Route::get('recent-bet/{event}', [RecentBetController::class, 'show'])->middleware(['auth:sanctum', 'teller']);
    Route::get('bet-history', [BettingController::class, 'index'])->middleware(['auth:sanctum', 'teller']);
    Route::post('betting', [BettingController::class, 'store'])->name('api.teller.bet')->middleware(['auth:sanctum', 'teller']);
    Route::post('cancel-bet', [BettingController::class, 'cancel'])->middleware(['auth:sanctum', 'teller']);
    Route::get('claim-history', [ClaimController::class, 'index'])->middleware(['auth:sanctum', 'teller']);
    Route::post('check-ticket', [ClaimController::class, 'checkTicket'])->middleware(['auth:sanctum', 'teller']);
    Route::post('claim-ticket', [ClaimController::class, 'claimTicket'])->middleware(['auth:sanctum', 'teller']);
    Route::post('generate-manual-ticket', [GenerateManualRefController::class, 'store'])->middleware(['auth:sanctum', 'teller']);
});
