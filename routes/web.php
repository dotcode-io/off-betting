<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('login', 'auth.login')->name('login')->middleware('guest');
Volt::route('playground', 'playground')->name('playground');
Route::prefix('app')->middleware('auth')->group(function () {
    Volt::route('dashboard', 'home')->name('dashboard');
    Volt::route('events', 'dashboard.admin.event-index')->name('events.index');
});
