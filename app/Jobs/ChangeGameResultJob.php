<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use App\Models\AppSetting;
use App\Models\Bet;
use App\Models\EventGame;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

final class ChangeGameResultJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $gameId, public GameResult $newResult)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $game = EventGame::query()->find($this->gameId);

        $drawEarnings = 0;
        $odds = 0;
        $gb_earnings = 0;

        if ($this->newResult === GameResult::CANCELLED) {
            Bet::query()->where('event_game_id', $this->gameId)
                ->update([
                    'result' => $this->newResult,
                    'win_amount' => DB::raw('bet_amount'),
                    'status' => BetStatus::Refund,
                ]);
            $game->update([
                'result' => $this->newResult,
                'earnings' => 0,
                'draw_earnings' => 0,
                'gb_earnings' => 0,
            ]);

            return;
        }

        if ($this->newResult === GameResult::MERON) {
            $odds = $game->meron_odds;
        }
        if ($this->newResult === GameResult::WALA) {
            $odds = $game->wala_odds;
        }
        if ($this->newResult === GameResult::DRAW) {
            $odds = 800;
            $drawEarnings = -$game->draw_bets * 8;
            $earnings = 0;
            Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', '!=', BetSide::Draw)
                ->update([
                    'result' => $this->newResult,
                    'win_amount' => DB::raw('bet_amount'),
                    'status' => BetStatus::Refund,
                ]);
        } else {
            Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', '!=', $this->newResult->side())
                ->update([
                    'status' => BetStatus::Loser,
                    'result' => $this->newResult,
                    'win_amount' => 0,
                ]);
            $earnings = ($game->meron_bets + $game->wala_bets - $game->gb_bets) * (AppSetting::query()->first()->plasada / 100);

            $gb_win = Bet::query()->where('event_game_id', $this->gameId)
                ->where('side', $this->newResult->side())
                ->where('user_id', 2)
                ->sum('bet_amount') * ($game->plasada / 100);

            $gb_earnings = ($gb_win - $game->gb_bets) + ($game->gb_bets * (AppSetting::query()->first()->plasada / 100));

        }

        $game->update([
            'result' => $this->newResult,
            'draw_earnings' => $drawEarnings,
            'earnings' => $earnings,
            'gb_earnings' => $this->newResult === GameResult::DRAW ? 0 : $gb_earnings,
        ]);

        Bet::query()->where('event_game_id', $this->gameId)
            ->where('side', $this->newResult->side())
            ->chunk(20, function ($bets) use ($odds): void {
                $this->updateBets($bets, $odds);
            });
    }

    private function roundDown(float|int $amount): float|int
    {
        return floor(($amount * 100) / 100);
    }

    private function updateBets(\Illuminate\Support\Collection $bets, $odds): void
    {
        DB::transaction(function () use ($bets, $odds): void {
            foreach ($bets as $bet) {
                $amount = $bet->bet_amount * ($odds / 100);
                $resultAmount = $this->roundDown($amount);
                $bet->update([
                    'status' => BetStatus::Winner,
                    'result' => $this->newResult,
                    'win_amount' => $resultAmount,
                ]);
            }
        });

    }
}
