<?php

declare(strict_types=1);

beforeEach(function () {
    $this->user = App\Models\User::factory()->player()->create();
    $this->event = App\Models\Event::factory()->create();
    $this->actingAs($this->user);
});

test('test event index', function () {
    $response = $this->get(route('events.index'));

    expect($response->status())->toBe(200);
});
