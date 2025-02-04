<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EventGame extends Model
{
    use HasFactory;

    public $casts = [
        'result' => GameResult::class,
        'status' => GameStatus::class,
    ];

    /**
     * @throws Exception
     */
    public function getOdds(): float
    {
        return (float) match ($this->result) {
            GameResult::MERON => $this->meron_odds,
            GameResult::WALA => $this->wala_odds,
            GameResult::DRAW => 800.00,
            default => 0.00,
        };

    }

    public function getRankings(): array
    {
        return Bet::query()
            ->select('id', 'bet_amount', 'reference_no', 'side')
            ->where('event_game_id', $this->id)
            ->orderByDesc('bet_amount')
            ->take(10)
            ->get()->map(fn ($bet): array => [
                'id' => $bet->id,
                'ref' => $bet->reference_no,
                'side' => $bet->side->label(),
                'amount' => number_format((float) $bet->bet_amount, 2),
                'side_color' => $bet->side->color(),
            ])->toArray();
    }
}
