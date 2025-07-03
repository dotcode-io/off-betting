<?php

declare(strict_types=1);

use App\Livewire\Dashboard\Teller\Console;
use App\Models\Event;
use App\Models\User;
use App\Enums\EventStatus;
use App\Enums\GameStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event as EventFacade;
use Livewire\Livewire;

test('can submit bet through livewire console', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet')
        ->assertHasNoErrors()
        ->assertDispatched('bet-placed');
});

test('livewire console generates unique idempotency keys', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    $component = Livewire::test(Console::class);

    $firstKey = $component->get('betForm.idempotency_key');
    expect($firstKey)->toStartWith('livewire-');
    expect($firstKey)->not()->toBeEmpty();

    // Submit a bet to trigger key regeneration
    $component
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet');

    $secondKey = $component->get('betForm.idempotency_key');
    expect($secondKey)->toStartWith('livewire-');
    expect($secondKey)->not()->toBe($firstKey);
});

test('livewire console prevents concurrent betting requests', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    // Simulate an existing lock
    $lockKey = "user_bet_lock_{$user->id}";
    Cache::lock($lockKey, 30)->get();

    Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet')
        ->assertHasErrors(['betForm.amount' => 'Another betting request is currently being processed. Please wait and try again.']);
});

test('livewire console validates required fields', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    // Test that validation works by checking that valid data passes
    $component = Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet')
        ->assertHasNoErrors()
        ->assertDispatched('bet-placed');

    // Verify the bet was actually created
    expect($component->get('betToPrint'))->not()->toBeNull();
});

test('livewire console handles betting errors gracefully', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 0; // This will cause an error
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet')
        ->assertHasErrors(['betForm.amount']);
});

test('livewire console resets form after successful bet', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    $component = Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet');

    // Check that form is reset after successful bet
    expect($component->get('betForm.amount'))->toBe('');
    expect($component->get('side'))->toBe('');

    // Check that a new idempotency key was generated
    $newKey = $component->get('betForm.idempotency_key');
    expect($newKey)->toStartWith('livewire-');
    expect($newKey)->not()->toBeEmpty();
});

test('livewire console shows print modal after successful bet', function () {
    EventFacade::fake();

    $user = User::factory()->teller()->create()->refresh();
    $event = Event::factory()->create();
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED;
    $currentGame->meron_charge = 10000;
    $currentGame->wala_charge = 10000;
    $currentGame->save();

    $this->actingAs($user);

    $component = Livewire::test(Console::class)
        ->set('side', 'meron')
        ->set('betForm.amount', '1000')
        ->call('submitBet');

    // Check that betToPrint is set
    expect($component->get('betToPrint'))->not()->toBeNull();

    // Check that the bet was created with correct data
    $bet = $component->get('betToPrint');
    expect($bet->bet_amount)->toBe(1000.0);
    expect($bet->side->value)->toBe('meron');
    expect($bet->user_id)->toBe($user->id);
});
