<?php

declare(strict_types=1);

beforeEach(function () {
    $this->user = App\Models\User::factory()->player()->create();
    $this->actingAs($this->user);
});

// test settings page status code 200
test('test settings page status code 200', function () {
    $response = $this->get(route('profile.settings'));

    expect($response->status())->toBe(200);
});
