<?php

declare(strict_types=1);

use Livewire\Livewire;

test('test see login page', function () {
    $response = $this->get('/login');
    expect($response->status())->toBe(200);

});

test('test user to login with invalid credentials', function () {
    Livewire::test('auth.login')
        ->set('form.username', 'testuser')
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.username']);

});

test('test user to login with invalid password', function () {
    $user = App\Models\User::factory([
        'password' => bcrypt('password'),
        'username' => 'testuser',
        'user_type' => 'admin',
    ])->create();

    Livewire::test('auth.login')
        ->set('form.username', $user->username)
        ->set('form.password', 'password1')
        ->call('login')
        ->assertHasErrors(['form.username']);
});

// test throttling
test('test user to login with invalid password multiple times', function () {
    $user = App\Models\User::factory([
        'password' => bcrypt('password'),
        'username' => 'testuser',
        'user_type' => 'admin',
    ])->create();

    for ($i = 0; $i < 6; $i++) {
        Livewire::test('auth.login')
            ->set('form.username', $user->username)
            ->set('form.password', 'password1')
            ->call('login');
    }

    Livewire::test('auth.login')
        ->set('form.username', $user->username)
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.username']);
});

test('test user to login successuflly', function () {
    $user = App\Models\User::factory([
        'password' => bcrypt('password'),
        'username' => 'testuser',
        'user_type' => 'admin',
    ])->create();

    Livewire::test('auth.login')
        ->set('form.username', $user->username)
        ->set('form.password', 'password')
        ->call('login')
        ->assertRedirect(route('dashboard'));

});

test('test user to logout', function () {
    $user = App\Models\User::factory([
        'password' => bcrypt('password'),
        'username' => 'testuser',
        'user_type' => 'admin',
    ])->create();

    Livewire::actingAs($user)
        ->test('dashboard.profile.settings')
        ->call('logout')
        ->assertRedirect(route('login'));
});
