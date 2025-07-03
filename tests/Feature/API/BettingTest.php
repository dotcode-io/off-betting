<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;

test('can teller bet', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();
    $betAmount = 1000;
    $response = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $betAmount,
        'side' => 'meron',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $response->assertStatus(201);

});

test('bet test low odds', function ($amount, $side) {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->meron_charge = 50000;
    $currentGame->wala_charge = 50000;
    $currentGame->meron_bets = 20000;
    $currentGame->meron_odds = 160;
    $currentGame->save();
    $response1 = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $amount,
        'side' => $side,
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);

    $response1->assertStatus(201);

})->with([
    [
        'amount' => 20000,
        'side' => 'meron',
    ],
    [
        'amount' => 20000,
        'side' => 'wala',
    ],
]);

test('can teller bet with ref', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();
    $betAmount = 1000;

    $action = new App\Actions\CreateManualRefAction();
    $reference = $action->handle(1);

    $response = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $betAmount,
        'side' => 'meron',
        'ref' => $reference[0]['ref'],
        'has_printer' => false,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);

    $response->assertStatus(201);

});

test('prevents duplicate bets with same idempotency key', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $idempotencyKey = 'duplicate-test-' . uniqid();
    $betData = [
        'amount' => 1000,
        'side' => 'meron',
        'has_printer' => true,
        'idempotency_key' => $idempotencyKey,
    ];

    // First request should succeed
    $response1 = $this->actingAs($user)->postJson(route('api.teller.bet'), $betData);
    $response1->assertStatus(201);

    // Second request with same idempotency key should fail
    $response2 = $this->actingAs($user)->postJson(route('api.teller.bet'), $betData);
    $response2->assertStatus(422);
    $response2->assertJsonValidationErrors(['idempotency_key']);
});
