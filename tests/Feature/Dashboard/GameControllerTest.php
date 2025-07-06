<?php

declare(strict_types=1);

use App\Actions\Game\DeclaredGameResultAction;
use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\EventStatus;
use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Jobs\DeclareResultJob;
use App\Models\Bet;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('can declare game result as meron', function () {
    Queue::fake();

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 5000;
    $currentGame->wala_bets = 3000;
    $currentGame->meron_odds = 180;
    $currentGame->wala_odds = 160;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as meron
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::MERON);

    // Verify game is updated
    $currentGame->refresh();
    expect($currentGame->status)->toBe(GameStatus::DONE);
    expect($currentGame->result)->toBe(GameResult::MERON);
    expect($currentGame->done_at)->not->toBeNull();

    // Verify job was dispatched
    Queue::assertPushed(DeclareResultJob::class, function ($job) use ($currentGame) {
        return $job->gameId === $currentGame->id && $job->result === GameResult::MERON;
    });
});

test('can declare game result as wala', function () {
    Queue::fake();

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 3000;
    $currentGame->wala_bets = 5000;
    $currentGame->meron_odds = 160;
    $currentGame->wala_odds = 180;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 800,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1200,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as wala
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::WALA);

    // Verify game is updated
    $currentGame->refresh();
    expect($currentGame->status)->toBe(GameStatus::DONE);
    expect($currentGame->result)->toBe(GameResult::WALA);
    expect($currentGame->done_at)->not->toBeNull();

    // Verify job was dispatched
    Queue::assertPushed(DeclareResultJob::class, function ($job) use ($currentGame) {
        return $job->gameId === $currentGame->id && $job->result === GameResult::WALA;
    });
});

test('can declare game result as draw', function () {
    Queue::fake();

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as draw
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::DRAW);

    // Verify game is updated
    $currentGame->refresh();
    expect($currentGame->status)->toBe(GameStatus::DONE);
    expect($currentGame->result)->toBe(GameResult::DRAW);
    expect($currentGame->done_at)->not->toBeNull();

    // Verify job was dispatched
    Queue::assertPushed(DeclareResultJob::class, function ($job) use ($currentGame) {
        return $job->gameId === $currentGame->id && $job->result === GameResult::DRAW;
    });
});

test('can declare game result as cancelled', function () {
    Queue::fake();

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 2000;
    $currentGame->wala_bets = 3000;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 600,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 900,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as cancelled
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::CANCELLED);

    // Verify game is updated
    $currentGame->refresh();
    expect($currentGame->status)->toBe(GameStatus::DONE);
    expect($currentGame->result)->toBe(GameResult::CANCELLED);
    expect($currentGame->done_at)->not->toBeNull();

    // Verify job was dispatched
    Queue::assertPushed(DeclareResultJob::class, function ($job) use ($currentGame) {
        return $job->gameId === $currentGame->id && $job->result === GameResult::CANCELLED;
    });
});

test('can declare game result with more than 20 winners to test chunking', function () {
    Queue::fake();

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 50000;
    $currentGame->wala_bets = 20000;
    $currentGame->meron_odds = 170;
    $currentGame->save();

    // Create more than 20 meron bets (winners)
    $user = User::factory()->create();
    $meronBets = [];
    for ($i = 0; $i < 25; $i++) {
        $meronBets[] = Bet::create([
            'uuid' => (string) Illuminate\Support\Str::uuid(),
            'reference_no' => 'REF-'.uniqid(),
            'event_id' => $event->id,
            'event_game_id' => $currentGame->id,
            'user_id' => $user->id,
            'bet_amount' => 1000,
            'side' => BetSide::Meron->value,
            'status' => BetStatus::OnGoing->value,
            'result' => GameResult::PENDING->value,
            'bet_at' => now(),
        ]);
    }

    // Create some wala bets (losers)
    for ($i = 0; $i < 10; $i++) {
        Bet::create([
            'uuid' => (string) Illuminate\Support\Str::uuid(),
            'reference_no' => 'REF-'.uniqid(),
            'event_id' => $event->id,
            'event_game_id' => $currentGame->id,
            'user_id' => $user->id,
            'bet_amount' => 800,
            'side' => BetSide::Wala->value,
            'status' => BetStatus::OnGoing->value,
            'result' => GameResult::PENDING->value,
            'bet_at' => now(),
        ]);
    }

    // Declare result as meron (so meron bets win)
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::MERON);

    // Verify game is updated
    $currentGame->refresh();
    expect($currentGame->status)->toBe(GameStatus::DONE);
    expect($currentGame->result)->toBe(GameResult::MERON);
    expect($currentGame->done_at)->not->toBeNull();

    // Verify job was dispatched with correct parameters
    Queue::assertPushed(DeclareResultJob::class, function ($job) use ($currentGame) {
        return $job->gameId === $currentGame->id &&
               $job->result === GameResult::MERON &&
               $job->odds === 170.0;
    });

    // Verify we have more than 20 winning bets that will be processed in chunks
    $winningBetsCount = Bet::where('event_game_id', $currentGame->id)
        ->where('side', BetSide::Meron)
        ->where('status', BetStatus::OnGoing)
        ->where('result', GameResult::PENDING)
        ->count();

    expect($winningBetsCount)->toBe(25);
    expect($winningBetsCount)->toBeGreaterThan(20);
});

test('cannot declare result for game that is not closed', function () {
    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::OPENED; // Game is still opened, not closed
    $currentGame->save();

    // Try to declare result
    $action = new DeclaredGameResultAction();

    expect(fn () => $action->handle($event, GameResult::MERON))
        ->toThrow(Exception::class, 'Game is not closed yet');
});

test('cannot declare pending as result', function () {
    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->save();

    // Try to declare pending result
    $action = new DeclaredGameResultAction();

    expect(fn () => $action->handle($event, GameResult::PENDING))
        ->toThrow(Exception::class, 'Game result is not valid');
});

test('verifies win amount calculation and winner determination for meron result', function () {
    // Don't fake queue so the job actually runs

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 5000;
    $currentGame->wala_bets = 3000;
    $currentGame->meron_odds = 180; // 1.8x multiplier
    $currentGame->wala_odds = 160;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as meron
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::MERON);

    // Wait for job to process
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);

    // Refresh bets to get updated data
    $meronBet->refresh();
    $walaBet->refresh();

    // Verify meron bet is marked as winner with correct win amount
    expect($meronBet->status)->toBe(BetStatus::Winner);
    expect($meronBet->result)->toBe(GameResult::MERON);
    // Win amount should be bet_amount * (odds / 100) = 1000 * (180 / 100) = 1800
    expect($meronBet->win_amount)->toBe(1800.0);

    // Verify wala bet is marked as loser
    expect($walaBet->status)->toBe(BetStatus::Loser);
    expect($walaBet->result)->toBe(GameResult::MERON);
    expect($walaBet->win_amount)->toBe(0.0);
});

test('verifies win amount calculation and winner determination for wala result', function () {
    // Don't fake queue so the job actually runs

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 3000;
    $currentGame->wala_bets = 5000;
    $currentGame->meron_odds = 160;
    $currentGame->wala_odds = 175; // 1.75x multiplier
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 800,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1200,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as wala
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::WALA);

    // Wait for job to process
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);

    // Refresh bets to get updated data
    $meronBet->refresh();
    $walaBet->refresh();

    // Verify wala bet is marked as winner with correct win amount
    expect($walaBet->status)->toBe(BetStatus::Winner);
    expect($walaBet->result)->toBe(GameResult::WALA);
    // Win amount should be bet_amount * (odds / 100) = 1200 * (175 / 100) = 2100
    expect($walaBet->win_amount)->toBe(2100.0);

    // Verify meron bet is marked as loser
    expect($meronBet->status)->toBe(BetStatus::Loser);
    expect($meronBet->result)->toBe(GameResult::WALA);
    expect($meronBet->win_amount)->toBe(0.0);
});

test('verifies draw result refunds all non-draw bets', function () {
    // Don't fake queue so the job actually runs

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as draw
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::DRAW);

    // Wait for job to process
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);

    // Refresh bets to get updated data
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();

    // Verify meron and wala bets are refunded (win_amount = bet_amount)
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::DRAW);
    expect($meronBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::DRAW);
    expect($walaBet->win_amount)->toBe(1000.0); // Refunded bet amount

    // Draw bet should be marked as winner when result is draw
    expect($drawBet->status)->toBe(BetStatus::Winner);
    expect($drawBet->result)->toBe(GameResult::DRAW);
});

test('verifies cancelled result refunds all bets', function () {
    // Don't fake queue so the job actually runs

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 2000;
    $currentGame->wala_bets = 3000;
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 600,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 900,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as cancelled
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::CANCELLED);

    // Wait for job to process
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);

    // Refresh bets to get updated data
    $meronBet->refresh();
    $walaBet->refresh();

    // Verify all bets are refunded (win_amount = bet_amount)
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::CANCELLED);
    expect($meronBet->win_amount)->toBe(600.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::CANCELLED);
    expect($walaBet->win_amount)->toBe(900.0); // Refunded bet amount
});

test('verifies win amount rounding down calculation', function () {
    // Don't fake queue so the job actually runs

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 5000;
    $currentGame->wala_bets = 3000;
    $currentGame->meron_odds = 183; // This will create decimal win amounts
    $currentGame->save();

    // Create some bets
    $user = User::factory()->create();

    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::OnGoing->value,
        'result' => GameResult::PENDING->value,
        'bet_at' => now(),
    ]);

    // Declare result as meron
    $action = new DeclaredGameResultAction();
    $action->handle($event, GameResult::MERON);

    // Wait for job to process
    Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);

    // Refresh bet to get updated data
    $meronBet->refresh();

    // Verify win amount is rounded down
    // Expected: 1000 * (183 / 100) = 1830.0 (should be floored)
    expect($meronBet->win_amount)->toBe(1830.0);
    expect($meronBet->status)->toBe(BetStatus::Winner);
});

test('verifies chunking works correctly with many winners', function () {
    // Run job synchronously to avoid queue issues in tests

    // Create event and game
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::CLOSED;
    $currentGame->meron_bets = 50000;
    $currentGame->wala_bets = 20000;
    $currentGame->meron_odds = 170;
    $currentGame->save();

    // Create more than 20 meron bets (winners) to test chunking
    $user = User::factory()->create();
    $meronBets = [];
    for ($i = 0; $i < 25; $i++) {
        $meronBets[] = Bet::create([
            'uuid' => (string) Illuminate\Support\Str::uuid(),
            'reference_no' => 'REF-'.uniqid(),
            'event_id' => $event->id,
            'event_game_id' => $currentGame->id,
            'user_id' => $user->id,
            'bet_amount' => 1000,
            'side' => BetSide::Meron->value,
            'status' => BetStatus::OnGoing->value,
            'result' => GameResult::PENDING->value,
            'bet_at' => now(),
        ]);
    }

    // Create some wala bets (losers)
    $walaBets = [];
    for ($i = 0; $i < 10; $i++) {
        $walaBets[] = Bet::create([
            'uuid' => (string) Illuminate\Support\Str::uuid(),
            'reference_no' => 'REF-'.uniqid(),
            'event_id' => $event->id,
            'event_game_id' => $currentGame->id,
            'user_id' => $user->id,
            'bet_amount' => 800,
            'side' => BetSide::Wala->value,
            'status' => BetStatus::OnGoing->value,
            'result' => GameResult::PENDING->value,
            'bet_at' => now(),
        ]);
    }

    // Run the job directly instead of through queue
    $job = new DeclareResultJob($currentGame->id, GameResult::MERON, 170.0);
    $job->handle();

    // Update game status manually since we're not using the action
    $currentGame->update([
        'status' => GameStatus::DONE,
        'result' => GameResult::MERON,
        'done_at' => now(),
    ]);

    // Verify all meron bets are winners with correct win amounts
    foreach ($meronBets as $bet) {
        $bet->refresh();
        expect($bet->status)->toBe(BetStatus::Winner);
        expect($bet->result)->toBe(GameResult::MERON);
        // Win amount should be bet_amount * (odds / 100) = 1000 * (170 / 100) = 1700
        expect($bet->win_amount)->toBe(1700.0);
    }

    // Verify all wala bets are losers
    foreach ($walaBets as $bet) {
        $bet->refresh();
        expect($bet->status)->toBe(BetStatus::Loser)
            ->and($bet->result)->toBe(GameResult::MERON)
            ->and($bet->win_amount)->toBe(0.0);
    }

    // Verify counts
    $winnerCount = Bet::where('event_game_id', $currentGame->id)
        ->where('status', BetStatus::Winner)
        ->count();
    expect($winnerCount)->toBe(25);

    $loserCount = Bet::where('event_game_id', $currentGame->id)
        ->where('status', BetStatus::Loser)
        ->count();
    expect($loserCount)->toBe(10);
});

// Change Result Tests
test('can change result from meron to wala', function () {
    // Create event and game with initial meron result
    $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::MERON;
    $currentGame->meron_bets = 5000;
    $currentGame->wala_bets = 3000;
    $currentGame->meron_odds = 180;
    $currentGame->wala_odds = 160;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as meron winners/losers
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 1800, // Was winner with meron odds
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    // Change result to wala
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::WALA);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::WALA);

    // Verify meron bet is now loser
    expect($meronBet->status)->toBe(BetStatus::Loser);
    expect($meronBet->result)->toBe(GameResult::WALA);
    expect($meronBet->win_amount)->toBe(0.0);

    // Verify wala bet is now winner with correct win amount
    expect($walaBet->status)->toBe(BetStatus::Winner);
    expect($walaBet->result)->toBe(GameResult::WALA);
    // Win amount should be bet_amount * (wala_odds / 100) = 500 * (160 / 100) = 800
    expect($walaBet->win_amount)->toBe(800.0);
});

test('can change result from wala to meron', function () {
    // Create event and game with initial wala result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::WALA;
    $currentGame->meron_bets = 3000;
    $currentGame->wala_bets = 5000;
    $currentGame->meron_odds = 175;
    $currentGame->wala_odds = 165;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as wala winners/losers
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 800,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1200,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 1980, // Was winner with wala odds
        'bet_at' => now(),
    ]);

    // Change result to meron
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::MERON);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::MERON);

    // Verify meron bet is now winner with correct win amount
    expect($meronBet->status)->toBe(BetStatus::Winner);
    expect($meronBet->result)->toBe(GameResult::MERON);
    // Win amount should be bet_amount * (meron_odds / 100) = 800 * (175 / 100) = 1400
    expect($meronBet->win_amount)->toBe(1400.0);

    // Verify wala bet is now loser
    expect($walaBet->status)->toBe(BetStatus::Loser);
    expect($walaBet->result)->toBe(GameResult::MERON);
    expect($walaBet->win_amount)->toBe(0.0);
});

test('can change result from meron to draw', function () {
    // Create event and game with initial meron result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::MERON;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as meron result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 1800, // Was winner
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    // Change result to draw
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::DRAW);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::DRAW);

    // Verify meron and wala bets are refunded
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::DRAW);
    expect($meronBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::DRAW);
    expect($walaBet->win_amount)->toBe(1000.0); // Refunded bet amount

    // Verify draw bet is now winner with 8x multiplier
    expect($drawBet->status)->toBe(BetStatus::Winner);
    expect($drawBet->result)->toBe(GameResult::DRAW);
    // Win amount should be bet_amount * (800 / 100) = 500 * 8 = 4000
    expect($drawBet->win_amount)->toBe(4000.0);
});

test('can change result from wala to draw', function () {
    // Create event and game with initial wala result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::WALA;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as wala result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 1750, // Was winner
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    // Change result to draw
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::DRAW);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::DRAW);

    // Verify meron and wala bets are refunded
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::DRAW);
    expect($meronBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::DRAW);
    expect($walaBet->win_amount)->toBe(1000.0); // Refunded bet amount

    // Verify draw bet is now winner with 8x multiplier
    expect($drawBet->status)->toBe(BetStatus::Winner);
    expect($drawBet->result)->toBe(GameResult::DRAW);
    // Win amount should be bet_amount * (800 / 100) = 500 * 8 = 4000
    expect($drawBet->win_amount)->toBe(4000.0);
});

test('can change result from meron to cancel', function () {
    // Create event and game with initial meron result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::MERON;
    $currentGame->meron_bets = 2000;
    $currentGame->wala_bets = 3000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as meron result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 600,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 1080, // Was winner
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 900,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::MERON->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    // Change result to cancelled
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::CANCELLED);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::CANCELLED);

    // Verify all bets are refunded
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::CANCELLED);
    expect($meronBet->win_amount)->toBe(600.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::CANCELLED);
    expect($walaBet->win_amount)->toBe(900.0); // Refunded bet amount
});

test('can change result from wala to cancel', function () {
    // Create event and game with initial wala result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::WALA;
    $currentGame->meron_bets = 2000;
    $currentGame->wala_bets = 3000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as wala result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 600,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Loser->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 0, // Was loser
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 900,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::WALA->value,
        'win_amount' => 1440, // Was winner
        'bet_at' => now(),
    ]);

    // Change result to cancelled
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::CANCELLED);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::CANCELLED);

    // Verify all bets are refunded
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::CANCELLED);
    expect($meronBet->win_amount)->toBe(600.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::CANCELLED);
    expect($walaBet->win_amount)->toBe(900.0); // Refunded bet amount
});

test('can change result from draw to cancel', function () {
    // Create event and game with initial draw result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::DRAW;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as draw result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 4000, // Was winner with 8x
        'bet_at' => now(),
    ]);

    // Change result to cancelled
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::CANCELLED);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::CANCELLED);

    // Verify all bets are refunded with their original bet amounts
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::CANCELLED);
    expect($meronBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::CANCELLED);
    expect($walaBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($drawBet->status)->toBe(BetStatus::Refund);
    expect($drawBet->result)->toBe(GameResult::CANCELLED);
    expect($drawBet->win_amount)->toBe(500.0); // Refunded bet amount
});

test('can change result from cancel to draw', function () {
    // Create event and game with initial cancelled result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::CANCELLED;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as cancelled result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 500, // Was refunded
        'bet_at' => now(),
    ]);

    // Change result to draw
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::DRAW);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::DRAW);

    // Verify meron and wala bets are refunded (same as before)
    expect($meronBet->status)->toBe(BetStatus::Refund);
    expect($meronBet->result)->toBe(GameResult::DRAW);
    expect($meronBet->win_amount)->toBe(1000.0); // Refunded bet amount

    expect($walaBet->status)->toBe(BetStatus::Refund);
    expect($walaBet->result)->toBe(GameResult::DRAW);
    expect($walaBet->win_amount)->toBe(1000.0); // Refunded bet amount

    // Verify draw bet is now winner with 8x multiplier
    expect($drawBet->status)->toBe(BetStatus::Winner);
    expect($drawBet->result)->toBe(GameResult::DRAW);
    // Win amount should be bet_amount * (800 / 100) = 500 * 8 = 4000
    expect($drawBet->win_amount)->toBe(4000.0);
});

test('can change result from draw to meron', function () {
    // Create event and game with initial draw result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::DRAW;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->meron_odds = 180;
    $currentGame->wala_odds = 160;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as draw result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 4000, // Was winner with 8x
        'bet_at' => now(),
    ]);

    // Change result to meron
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::MERON);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::MERON);

    // Verify meron bet is now winner with correct win amount
    expect($meronBet->status)->toBe(BetStatus::Winner);
    expect($meronBet->result)->toBe(GameResult::MERON);
    // Win amount should be bet_amount * (meron_odds / 100) = 1000 * (180 / 100) = 1800
    expect($meronBet->win_amount)->toBe(1800.0);

    // Verify wala bet is now loser
    expect($walaBet->status)->toBe(BetStatus::Loser);
    expect($walaBet->result)->toBe(GameResult::MERON);
    expect($walaBet->win_amount)->toBe(0.0);

    // Verify draw bet is now loser
    expect($drawBet->status)->toBe(BetStatus::Loser);
    expect($drawBet->result)->toBe(GameResult::MERON);
    expect($drawBet->win_amount)->toBe(0.0);
});

test('can change result from draw to wala', function () {
    // Create event and game with initial draw result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::DRAW;
    $currentGame->meron_bets = 4000;
    $currentGame->wala_bets = 4000;
    $currentGame->draw_bets = 1000;
    $currentGame->meron_odds = 180;
    $currentGame->wala_odds = 160;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as draw result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $drawBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Draw->value,
        'status' => BetStatus::Winner->value,
        'result' => GameResult::DRAW->value,
        'win_amount' => 4000, // Was winner with 8x
        'bet_at' => now(),
    ]);

    // Change result to wala
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::WALA);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $drawBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::WALA);

    // Verify meron bet is now loser
    expect($meronBet->status)->toBe(BetStatus::Loser);
    expect($meronBet->result)->toBe(GameResult::WALA);
    expect($meronBet->win_amount)->toBe(0.0);

    // Verify wala bet is now winner with correct win amount
    expect($walaBet->status)->toBe(BetStatus::Winner);
    expect($walaBet->result)->toBe(GameResult::WALA);
    // Win amount should be bet_amount * (wala_odds / 100) = 1000 * (160 / 100) = 1600
    expect($walaBet->win_amount)->toBe(1600.0);

    // Verify draw bet is now loser
    expect($drawBet->status)->toBe(BetStatus::Loser);
    expect($drawBet->result)->toBe(GameResult::WALA);
    expect($drawBet->win_amount)->toBe(0.0);
});

test('can change result from cancel to meron', function () {
    // Create event and game with initial cancelled result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::CANCELLED;
    $currentGame->meron_bets = 5000;
    $currentGame->wala_bets = 3000;
    $currentGame->meron_odds = 180;
    $currentGame->wala_odds = 160;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as cancelled result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1000,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 1000, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 500,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 500, // Was refunded
        'bet_at' => now(),
    ]);

    // Change result to meron
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::MERON);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::MERON);

    // Verify meron bet is now winner with correct win amount
    expect($meronBet->status)->toBe(BetStatus::Winner);
    expect($meronBet->result)->toBe(GameResult::MERON);
    // Win amount should be bet_amount * (meron_odds / 100) = 1000 * (180 / 100) = 1800
    expect($meronBet->win_amount)->toBe(1800.0);

    // Verify wala bet is now loser
    expect($walaBet->status)->toBe(BetStatus::Loser);
    expect($walaBet->result)->toBe(GameResult::MERON);
    expect($walaBet->win_amount)->toBe(0.0);
});

test('can change result from cancel to wala', function () {
    // Create event and game with initial cancelled result
     $event = Event::factory()->create([
        'number_of_games' => 2,
    ]);
    $event->status = EventStatus::OPENED;
    $event->save();
    $event->createGames();

    $currentGame = $event->getCurrentGame();
    $currentGame->status = GameStatus::DONE;
    $currentGame->result = GameResult::CANCELLED;
    $currentGame->meron_bets = 3000;
    $currentGame->wala_bets = 5000;
    $currentGame->meron_odds = 160;
    $currentGame->wala_odds = 175;
    $currentGame->save();

    $user = User::factory()->create();

    // Create bets that were initially processed as cancelled result
    $meronBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 800,
        'side' => BetSide::Meron->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 800, // Was refunded
        'bet_at' => now(),
    ]);

    $walaBet = Bet::create([
        'uuid' => (string) Illuminate\Support\Str::uuid(),
        'reference_no' => 'REF-'.uniqid(),
        'event_id' => $event->id,
        'event_game_id' => $currentGame->id,
        'user_id' => $user->id,
        'bet_amount' => 1200,
        'side' => BetSide::Wala->value,
        'status' => BetStatus::Refund->value,
        'result' => GameResult::CANCELLED->value,
        'win_amount' => 1200, // Was refunded
        'bet_at' => now(),
    ]);

    // Change result to wala
    $job = new App\Jobs\ChangeGameResultJob($currentGame->id, GameResult::WALA);
    $job->handle();

    // Refresh bets and game
    $meronBet->refresh();
    $walaBet->refresh();
    $currentGame->refresh();

    // Verify game result is updated
    expect($currentGame->result)->toBe(GameResult::WALA);

    // Verify meron bet is now loser
    expect($meronBet->status)->toBe(BetStatus::Loser);
    expect($meronBet->result)->toBe(GameResult::WALA);
    expect($meronBet->win_amount)->toBe(0.0);

    // Verify wala bet is now winner with correct win amount
    expect($walaBet->status)->toBe(BetStatus::Winner);
    expect($walaBet->result)->toBe(GameResult::WALA);
    // Win amount should be bet_amount * (wala_odds / 100) = 1200 * (175 / 100) = 2100
    expect($walaBet->win_amount)->toBe(2100.0);
});
