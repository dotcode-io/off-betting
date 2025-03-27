<?php

declare(strict_types=1);

namespace App\Actions\Console;

use App\Actions\User\DepositWallet;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Events\BetRankingsEvent;
use App\Events\GameEvent;
use App\Models\Bet;
use App\Models\Event;
use App\Models\ManualRef;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class BetAction
{
    public function __construct(public DepositWallet $addWalletAction)
    {
        //
    }

    public function handle(Event $event, BettingDataTransferObject $bettingDataTransferObject, ?string $ref): Bet
    {

        return DB::transaction(function () use ($event, $bettingDataTransferObject, $ref) {
            $id = Auth::id();
            $openGame = $event->getOpenGame();

            $side = BetSide::from($bettingDataTransferObject->side);

            if ($side === BetSide::Meron) {
                // increment meron_bets,meron_bettors

                $openGame->increment('meron_bets', $bettingDataTransferObject->amount);
                $openGame->increment('meron_bettors');

            }

            if ($side === BetSide::Wala) {
                // increment wala_bets,wala_bettors
                $openGame->increment('wala_bets', $bettingDataTransferObject->amount);
                $openGame->increment('wala_bettors');
            }

            if ($side === BetSide::Draw) {
                // increment draw_bets,draw_bettors
                $openGame->increment('draw_bets', $bettingDataTransferObject->amount);
                $openGame->increment('draw_bettors');
            }

            if (in_array($id, config('app.gb_ids')) && $side !== BetSide::Draw) {
                $openGame->increment('gb_bets', $bettingDataTransferObject->amount);
            }

            $totalBets = $openGame->meron_bets + $openGame->wala_bets;

            $openGame->update([
                'meron_odds' => $openGame->meron_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->meron_bets : 0,
                'wala_odds' => $openGame->wala_bets > 0 ? ($totalBets * (100 - $openGame->plasada)) / $openGame->wala_bets : 0,
            ]);

            if ($ref) {
                $referenceNo = $ref;
                $manualRef = ManualRef::query()->where('ref', $ref)->where('used', false)->firstOrFail();
                $manualRef->update(['used' => true]);
                $manualRef->save();
            } else {
                $betCount = Bet::where('event_id', $event->id)->where('event_game_id', $openGame->id)->count() + 1;
                $referenceNo = $event->id.'-'.$openGame->id.'-'.mb_str_pad((string) $betCount, 2, '0', STR_PAD_LEFT);
            }

            $bet = Bet::create(['reference_no' => $referenceNo,
                'event_id' => $event->id,
                'event_game_id' => $openGame->id,
                'user_id' => $id,
                'bet_amount' => $bettingDataTransferObject->amount,
                'side' => $side,
                'status' => BetStatus::OnGoing,
                'result' => GameResult::PENDING,
                'is_claimed' => false,
                'bet_at' => now(),
            ]);

            $this->addWalletAction->handle(Auth::user(), [
                'amount' => $bettingDataTransferObject->amount,
                'description' => 'Placed a bet on Event#'.$event->id,
            ]);

            GameEvent::dispatch($openGame, null);
            BetRankingsEvent::dispatch($event->uuid, $openGame->getRankings());

            return $bet;
        });
    }
}
