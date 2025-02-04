<?php

declare(strict_types=1);

namespace App\Actions\Console;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Events\BetRankingsEvent;
use App\Events\GameEvent;
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

            $side = BetSide::from($form->side);
            if ($side === BetSide::Meron) {
                // increment meron_bets,meron_bettors
                $openGame->increment('meron_bets', $form->amount);
                $openGame->increment('meron_bettors');

            }

            if ($side === BetSide::Wala) {
                // increment wala_bets,wala_bettors
                $openGame->increment('wala_bets', $form->amount);
                $openGame->increment('wala_bettors');
            }

            if ($side === BetSide::Draw) {
                // increment draw_bets,draw_bettors
                $openGame->increment('draw_bets', $form->amount);
                $openGame->increment('draw_bettors');
            }

            $totalBets = $openGame->meron_bets + $openGame->wala_bets;

            $openGame->update([
                'meron_odds' => $openGame->meron_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->meron_bets : 0,
                'wala_odds' => $openGame->wala_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->wala_bets : 0,
            ]);

            $bet = Bet::create([
                'reference_no' => 'B'.auth()->id().'-'.now()->format('ymd').'-'.now()->format('His'),
                'event_id' => $event->id,
                'event_game_id' => $openGame->id,
                'user_id' => Auth::id(),
                'bet_amount' => $form->amount,
                'side' => $side,
                'status' => BetStatus::OnGoing,
                'result' => GameResult::PENDING,
                'is_claimed' => false,
                'bet_at' => now(),
            ]);

            GameEvent::dispatch($openGame, null);
            BetRankingsEvent::dispatch($event->uuid, $openGame->getRankings());

            return $bet;
        });
    }
}
