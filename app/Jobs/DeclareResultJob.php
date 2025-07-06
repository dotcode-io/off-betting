<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Models\AppSetting;
use App\Models\Bet;
use App\Models\EventGame;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

final class DeclareResultJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $gameId, public GameResult $result, public float $odds)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        $earnings = 0;
        $gb_earnings = 0;
        $game = EventGame::query()->find($this->gameId);
        if ($this->result === GameResult::CANCELLED) {
            Bet::query()->where('event_game_id', $this->gameId)
                ->where('result', GameResult::PENDING)
                ->where('status', BetStatus::OnGoing)
                ->update([
                    'status' => BetStatus::Refund,
                    'result' => $this->result,
                    'win_amount' => DB::raw('bet_amount'),
                ]);

            return;
        }

        if ($this->result === GameResult::DRAW) {
            Bet::query()->where('event_game_id', $this->gameId)
                ->where('result', GameResult::PENDING)
                ->where('status', BetStatus::OnGoing)
                ->where('side', '!=', BetSide::Draw)
                ->update([
                    'status' => BetStatus::Refund,
                    'result' => $this->result,
                    'win_amount' => DB::raw('bet_amount'),
                ]);

            $drawEarnings = -$game->draw_bets * 8;

        } else {

            Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', '!=', $this->result->side())
                ->where('result', GameResult::PENDING)
                ->where('status', BetStatus::OnGoing)
                ->update([
                    'status' => BetStatus::Loser,
                    'result' => $this->result,
                ]);
            $drawEarnings = $game->draw_bets;
            $earnings = ($game->meron_bets + $game->wala_bets - $game->gb_bets) * ($game->plasada / 100);
            $gb_win = Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', $this->result->side())
                ->where('result', GameResult::PENDING)
                ->where('status', BetStatus::OnGoing)
                ->where('user_id', 2)
                ->sum('bet_amount') * ($game->plasada / 100);

            $gb_earnings = ($gb_win - $game->gb_bets) + ($game->gb_bets * ((AppSetting::query()->first()->plasada * 2) / 100));

        }

        $game->update([
            'earnings' => $earnings,
            'draw_earnings' => $drawEarnings,
            'gb_earnings' => $gb_earnings,
        ]);

        // Process winning bets in chunks, but use a while loop to ensure all bets are processed
        do {
            $bets = Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', $this->result->side())
                ->where('result', GameResult::PENDING)
                ->where('status', BetStatus::OnGoing)
                ->limit(20)
                ->get();

            foreach ($bets as $bet) {
                $amount = $bet->bet_amount * ($this->odds / 100);
                $resultAmount = $this->roundDown($amount);
                $bet->update([
                    'status' => BetStatus::Winner,
                    'result' => $this->result,
                    'win_amount' => $resultAmount,
                ]);
            }
        } while ($bets->count() > 0);



    }

    private function roundDown(float|int $amount): float|int
    {
        return floor(($amount * 100) / 100);
    }
}
