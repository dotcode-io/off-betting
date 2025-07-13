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
use App\Services\BetReferenceService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

final class BetAction
{
    public function __construct(public DepositWallet $addWalletAction, public BetReferenceService $betReferenceService)
    {
        //
    }

    /**
     * @throws Throwable
     */
    public function handle(Event $event, BettingDataTransferObject $bettingDataTransferObject, ?string $ref, string $idempotencyKey): Bet
    {


        return DB::transaction(function () use ($event, $bettingDataTransferObject, $ref, $idempotencyKey) {
            $id = Auth::id();
            $openGame = $event->getOpenGame();

            $side = BetSide::from($bettingDataTransferObject->side);

            if ($side === BetSide::Meron) {

                if ($openGame->meron_charge < 1) {
                    throw new Exception('Meron is not open');
                }

                //                $remaining = $openGame->meron_charge - $bettingDataTransferObject->amount;
                //
                //                if ($remaining < 0) {
                //                    throw new Exception('Meron amount bet must not greater than '.$openGame->meron_charge);
                //                }
                //
                //                if ($remaining === 0.00) {
                //
                //                    $openGame->increment('wala_charge', $event->charge);
                //                }

//                if ($openGame->meron_bets > 20000 && $openGame->meron_odds <= 160) {
//                    throw new Exception('Meron odds is too low');
//                }

                // increment meron_bets,meron_bettors

                $openGame->increment('meron_bets', $bettingDataTransferObject->amount);
                $openGame->increment('meron_bettors');
                // $openGame->decrement('meron_charge', $bettingDataTransferObject->amount);

            }

            if ($side === BetSide::Wala) {
                if ($openGame->wala_charge < 1) {
                    throw new Exception('Wala is not open');
                }
                //                $remaining = $openGame->wala_charge - $bettingDataTransferObject->amount;
                //
                //                if ($remaining < 0) {
                //                    throw new Exception('Wala amount bet must not greater than '.$openGame->wala_charge);
                //                }
                //
                //                if ($remaining === 0.00) {
                //                    $openGame->increment('meron_charge', $event->charge);
                //                }

//                if ($openGame->wala_bets > 20000 && $openGame->wala_odds <= 160) {
//                    throw new Exception('Wala odds is too low');
//                }

                // increment wala_bets,wala_bettors
                $openGame->increment('wala_bets', $bettingDataTransferObject->amount);
                $openGame->increment('wala_bettors');
                // $openGame->decrement('wala_charge', $bettingDataTransferObject->amount);
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
                $eventId = $event->id;
                $gameId = $openGame->id;
                $referenceNo = $this->betReferenceService->generate($eventId, $gameId);
            }

            if ($id === 2) {
                $gbRef = 'GB-'.$side->acronym().'-'.$event->id.'-'.$openGame->id;
                $bet = Bet::query()->where([
                    'reference_no' => $gbRef,
                ])->first();

                if ($bet) {
                    $bet->increment('bet_amount', $bettingDataTransferObject->amount);
                } else {
                    $bet = Bet::create(['reference_no' => $gbRef,
                        'event_id' => $event->id,
                        'event_game_id' => $openGame->id,
                        'user_id' => $id,
                        'bet_amount' => $bettingDataTransferObject->amount,
                        'side' => $side,
                        'status' => BetStatus::OnGoing,
                        'result' => GameResult::PENDING,
                        'is_claimed' => false,
                        'bet_at' => now(),
                        'idempotency_key' => $idempotencyKey,
                    ]);
                }
            } else {
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
                    'idempotency_key' => $idempotencyKey,
                ]);
            }

            if ($id !== 2) {
                $this->addWalletAction->handle(Auth::user(), [
                    'amount' => $bettingDataTransferObject->amount,
                    'description' => 'Placed a bet on Event#('.$event->id.')'.$event->name.' - Game#'.$openGame->game_number,
                ]);
            }

            GameEvent::dispatch($openGame, null);
            BetRankingsEvent::dispatch($event->uuid, $openGame->getRankings());

            return $bet;
        });
    }
}
