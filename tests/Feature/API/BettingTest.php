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

test('can cancel bet successfully', function () {
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

    // First create a bet
    $betResponse = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => 1000,
        'side' => 'meron',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $betResponse->assertStatus(201);
    $betId = $betResponse->json('bet.id');

    // Get wallet balance after placing bet (bet placement adds to wallet)
    $balanceAfterBet = $user->fresh()->wallet_amount;

    // Cancel the bet
    $cancelResponse = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => $betId,
    ]);

    $cancelResponse->assertStatus(200);
    $cancelResponse->assertJson([
        'message' => 'Bet cancelled successfully',
    ]);

    // Verify bet is deleted
    $this->assertDatabaseMissing('bets', ['id' => $betId]);

    // Verify wallet balance is adjusted (cancellation subtracts from wallet)
    $user->refresh();
    expect($user->wallet_amount)->toBe($balanceAfterBet - 1000);
});

test('cannot cancel bet with invalid bet_id', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();

    $response = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => 'invalid',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['bet_id']);
});

test('cannot cancel bet with non-existent bet_id', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();

    $response = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => 99999,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['bet_id']);
});

test('cannot cancel bet without bet_id', function () {
    Event::fake();
    $user = App\Models\User::factory()->teller()->create()->refresh();

    $response = $this->actingAs($user)->postJson('/api/teller/cancel-bet', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['bet_id']);
});

test('cannot cancel bet on closed game', function () {
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

    // First create a bet
    $betResponse = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => 1000,
        'side' => 'meron',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $betResponse->assertStatus(201);
    $betId = $betResponse->json('bet.id');

    // Close the game
    $currentGame->status = App\Enums\GameStatus::CLOSED;
    $currentGame->save();

    // Try to cancel the bet
    $cancelResponse = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => $betId,
    ]);

    $cancelResponse->assertStatus(400);
    $cancelResponse->assertJson([
        'message' => 'Can only cancel bets on opened games',
    ]);

    // Verify bet still exists
    $this->assertDatabaseHas('bets', ['id' => $betId]);
});

test('updates game statistics correctly when cancelling meron bet', function () {
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
    $currentGame->meron_bets = 5000;
    $currentGame->meron_bettors = 3;
    $currentGame->save();

    // Create a bet
    $betResponse = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => 1000,
        'side' => 'meron',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $betResponse->assertStatus(201);
    $betId = $betResponse->json('bet.id');

    // Get initial game stats
    $currentGame->refresh();
    $initialMeronBets = $currentGame->meron_bets;
    $initialMeronBettors = $currentGame->meron_bettors;

    // Cancel the bet
    $cancelResponse = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => $betId,
    ]);

    $cancelResponse->assertStatus(200);

    // Verify game statistics are updated
    $currentGame->refresh();
    expect($currentGame->meron_bets)->toBe($initialMeronBets - 1000);
    expect($currentGame->meron_bettors)->toBe($initialMeronBettors - 1);
});

test('updates game statistics correctly when cancelling wala bet', function () {
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
    $currentGame->wala_bets = 3000;
    $currentGame->wala_bettors = 2;
    $currentGame->save();

    // Create a bet
    $betResponse = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => 1500,
        'side' => 'wala',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $betResponse->assertStatus(201);
    $betId = $betResponse->json('bet.id');

    // Get initial game stats
    $currentGame->refresh();
    $initialWalaBets = $currentGame->wala_bets;
    $initialWalaBettors = $currentGame->wala_bettors;

    // Cancel the bet
    $cancelResponse = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => $betId,
    ]);

    $cancelResponse->assertStatus(200);

    // Verify game statistics are updated
    $currentGame->refresh();
    expect($currentGame->wala_bets)->toBe($initialWalaBets - 1500);
    expect($currentGame->wala_bettors)->toBe($initialWalaBettors - 1);
});

test('updates game statistics correctly when cancelling draw bet', function () {
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
    $currentGame->draw_bets = 2000;
    $currentGame->draw_bettors = 4;
    $currentGame->save();

    // Create a bet
    $betResponse = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => 800,
        'side' => 'draw',
        'has_printer' => true,
        'idempotency_key' => 'test-key-' . uniqid(),
    ]);
    $betResponse->assertStatus(201);
    $betId = $betResponse->json('bet.id');

    // Get initial game stats
    $currentGame->refresh();
    $initialDrawBets = $currentGame->draw_bets;
    $initialDrawBettors = $currentGame->draw_bettors;

    // Cancel the bet
    $cancelResponse = $this->actingAs($user)->postJson('/api/teller/cancel-bet', [
        'bet_id' => $betId,
    ]);

    $cancelResponse->assertStatus(200);

    // Verify game statistics are updated
    $currentGame->refresh();
    expect($currentGame->draw_bets)->toBe($initialDrawBets - 800);
    expect($currentGame->draw_bettors)->toBe($initialDrawBettors - 1);
});

test('requires authentication to cancel bet', function () {
    $response = $this->postJson('/api/teller/cancel-bet', [
        'bet_id' => 1,
    ]);

    $response->assertStatus(401);
});
