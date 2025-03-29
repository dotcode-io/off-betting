<?php

declare(strict_types=1);

test('can teller bet', function () {
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->save();
    $betAmount = 1000;
    $response = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $betAmount,
        'side' => 'meron',
    ]);
    $response->assertStatus(201);

});

test('bet test low odds', function ($amount, $side) {
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->save();
    $currentGame->meron_bets = 20000;
    $currentGame->meron_odds = 160;
    $currentGame->save();
    $response1 = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $amount,
        'side' => $side,
        'has_printer' => true,
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
    $user = App\Models\User::factory()->teller()->create()->refresh();
    $event = App\Models\Event::factory()->create();
    $event->status = App\Enums\EventStatus::OPENED;
    $event->save();
    $event->createGames();
    $currentGame = $event->getCurrentGame();
    $currentGame->status = App\Enums\GameStatus::OPENED;
    $currentGame->save();
    $betAmount = 1000;

    $action = new App\Actions\CreateManualRefAction();
    $reference = $action->handle(1);

    $response = $this->actingAs($user)->postJson(route('api.teller.bet'), [
        'amount' => $betAmount,
        'side' => 'meron',
        'ref' => $reference[0]['ref'],
    ]);

    $response->assertStatus(201);
    dd($response->json());

});
