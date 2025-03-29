<?php

declare(strict_types=1);

use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Models\Bet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('fix-winner', function () {
    $betList = Bet::query()->where('event_game_id', request('game_id'))
        ->where('side', request('side'))
        ->where('result', GameResult::PENDING)
        ->where('status', BetStatus::OnGoing)
        ->get();

    foreach ($betList as $bets) {
        DB::transaction(function () use ($bets): void {
            foreach ($bets as $bet) {
                $amount = $bet->bet_amount * ($this->odds / 100);
                $resultAmount = floor(($amount * 100) / 100);
                $bet->update([
                    'status' => BetStatus::Winner,
                    'result' => $this->result,
                    'win_amount' => $resultAmount,
                ]);
            }
        });
    }
});
Volt::route('login', 'auth.login')->name('login')->middleware('guest');

Route::get('/', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('dashboard');
    }

    if (auth()->user()->isTeller()) {
        return redirect()->route('teller.console');
    }

    if (auth()->user()->isController()) {
        return redirect()->route('controller.events.index');
    }
})->middleware(['auth', 'auth.session']);

Route::prefix('app')->middleware(['auth', 'auth.session'])->group(function () {
    Route::prefix('admin')->middleware('admin')->group(function () {
        Volt::route('dashboard', 'dashboard.index')->name('dashboard');

        Volt::route('events', 'dashboard.admin.event.index')->name('events.index');
        Volt::route('events/{event}', 'dashboard.admin.event.show')->name('events.show');
        Volt::route('game-controller', 'dashboard.admin.game-controller.index')->name('events.game-controller');
        Volt::route('game-controller/{event}', 'dashboard.admin.game-controller.show')->name('events.game-controller.show');

        Volt::route('settings', 'dashboard.profile.settings')->name('profile.settings');

        Volt::route('bet-history', 'dashboard.admin.bet-history-index')->name('bet-history');
        Volt::route('claim-history', 'dashboard.admin.claim-history-index')->name('claim-history');

        Volt::route('users', 'dashboard.admin.user-index')->name('users.index');
        Volt::route('report/{event}', 'report.report-index')->name('report.index');

    });

    Route::prefix('teller')->name('teller.')->middleware('teller')->group(function () {
        Volt::route('console', 'dashboard.teller.console')->name('console');
        Volt::route('bet-history', 'dashboard.teller.bet-history')->name('bet-history');
        Volt::route('claim-history', 'dashboard.teller.claim-history')->name('claim-history');
        Volt::route('claim-ticket', 'dashboard.teller.claim-ticket')->name('claim-ticket');
        Volt::route('summary', 'dashboard.teller.summary')->name('summary');
    });

    Route::prefix('controller')->name('controller.')->middleware('controller')->group(function () {
        Volt::route('game-controller', 'dashboard.controller.event-index')->name('events.index');
        Volt::route('game-controller/{event}', 'dashboard.admin.game-controller.show')->name('events.game-controller.show');
    });

    Volt::route('settings', 'dashboard.profile.settings')->name('profile.settings');

});

Volt::route('app/game-viewer/now', 'game-viewer.index')->name('game-viewer');
