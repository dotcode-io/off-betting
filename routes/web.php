<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('login', 'auth.login')->name('login')->middleware('guest');

Volt::route('/', 'home')->middleware('auth')->name('home');
Route::prefix('app')->middleware('auth')->group(function () {
    Route::prefix('admin')->middleware('admin')->group(function () {
        Volt::route('dashboard', 'home')->name('admin.dashboard');

        Volt::route('events', 'dashboard.admin.event-index')->name('events.index');
        Volt::route('game-controller', 'dashboard.admin.game-controller.index')->name('events.game-controller');
        Volt::route('game-controller/{event}', 'dashboard.admin.game-controller.show')->name('events.game-controller.show');
        Volt::route('settings', 'dashboard.profile.settings')->name('profile.settings');

        Volt::route('users', 'dashboard.admin.user-index')->name('users.index');

        Volt::route('console/{event}', 'dashboard.teller.console')->name('tell.console');
    });

    Route::prefix('teller')->name('teller.')->middleware('teller')->group(function () {
        Volt::route('dashboard', 'home')->name('dashboard');
    });

    Route::prefix('controller')->name('controller.')->middleware('controller')->group(function () {
        Volt::route('dashboard', 'home')->name('dashboard');
        Volt::route('events', 'dashboard.admin.event-index')->name('events.index');
        Volt::route('game-controller', 'dashboard.admin.game-controller.index')->name('events.game-controller');
        Volt::route('game-controller/{event}', 'dashboard.admin.game-controller.show')->name('events.game-controller.show');
    });
    
});
