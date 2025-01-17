<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'playground')->name('home');
Volt::route('login', 'auth.login')->name('login')->middleware('guest');
Volt::route('playground', 'playground')->middleware('auth')->name('playground');
Route::prefix('app')->middleware('auth')->group(function () {
    Volt::route('dashboard', 'home')->name('dashboard');
    Volt::route('events', 'dashboard.admin.event-index')->name('events.index');
    Volt::route('settings', 'dashboard.profile.settings')->name('profile.settings');
});
