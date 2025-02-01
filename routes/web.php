<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'playground')->name('home');
Volt::route('login', 'auth.login')->name('login')->middleware('guest');
Route::prefix('app')->middleware('auth')->group(function () {
    Volt::route('dashboard', 'home')->name('dashboard');

    Volt::route('events', 'dashboard.admin.event-index')->name('events.index');
    Volt::route('game-controller', 'dashboard.admin.game-controller.index')->name('events.game-controller');
    Volt::route('game-controller/{event}', 'dashboard.admin.game-controller.show')->name('events.game-controller.show');
    Volt::route('settings', 'dashboard.profile.settings')->name('profile.settings');

    Volt::route('console/{event}', 'dashboard.teller.console')->name('tell.console');
});
