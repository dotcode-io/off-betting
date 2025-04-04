<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\User\WithdrawalWallet;
use App\Enums\BetSide;
use App\Enums\GameStatus;
use App\Events\BetRankingsEvent;
use App\Events\GameEvent;
use App\Models\Bet;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CancelBetAction
{
    public function __construct(
        public WithdrawalWallet $withdrawalWalletAction,
    ) {
        //
    }

    /**
     * @throws Throwable
     */
    public function handle(
        int $betId,
    ): void {
        DB::transaction(function () use ($betId) {
            $bet = Bet::query()->with(['eventGame', 'user', 'event'])->findOrFail($betId);

            if ($bet->eventGame->status !== GameStatus::OPENED) {
                throw new Exception('Can only cancel bets on opened games');
            }

            $this->withdrawalWalletAction->handle(
                $bet->user,
                [
                    'amount' => $bet->bet_amount,
                    'description' => 'Bet cancelled',
                ]
            );

            $openGame = $bet->eventGame;

            if ($bet->side === BetSide::Meron) {
                $openGame->decrement('meron_bets', $bet->bet_amount);
                $openGame->decrement('meron_bettors');
            } else {
                $openGame->decrement('wala_bets', $bet->bet_amount);
                $openGame->decrement('wala_bettors');
            }
            $totalBets = $openGame->meron_bets + $openGame->wala_bets;
            $openGame->update([
                'meron_odds' => $openGame->meron_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->meron_bets : 0,
                'wala_odds' => $openGame->wala_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->wala_bets : 0,
            ]);
            $openGame->save();
            $event = $bet->event;

            GameEvent::dispatch($openGame, null);
            BetRankingsEvent::dispatch($event->uuid, $openGame->getRankings());

            $bet->delete();

        });
    }
}
