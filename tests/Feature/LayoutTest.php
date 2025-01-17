<?php

declare(strict_types=1);

test('test guest layout', function () {
    $response = $this->get('/');

    expect($response->status())->toBe(200);

});
test('test normal layout', function () {
    $user = App\Models\User::factory()->player()->create();

    $response = $this->actingAs($user)->get('/playground');

    expect($response->status())->toBe(200);
});
test('test admin layout', function () {

    $user = App\Models\User::factory()->admin()->create();

    $response = $this->actingAs($user)->get('/app/dashboard');

    expect($response->status())->toBe(200);
});
