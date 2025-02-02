<?php

declare(strict_types=1);

namespace App\Actions\Console;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Livewire\Forms\BetForm;
use App\Models\Bet;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class BetActions
{
    public function handle(Event $event, BetForm $form): Bet
    {

        return DB::transaction(function () use ($event, $form) {
            $openGame = $event->getOpenGame();

            return Bet::create([
                'reference_no' => 'B' . auth()->id() . '-' . now()->format('ymd') . '-' . now()->format('His'),
                'event_id' => $event->id,
                'event_game_id' => $openGame->id,
                'user_id' => Auth::id(),
                'bet_amount' => $form->amount,
                'side' => BetSide::from($form->side),
                'status' => BetStatus::OnGoing,
                'result' => GameResult::PENDING,
                'is_claimed' => false,
                'bet_at' => now(),
            ]);
        });
    }
}
