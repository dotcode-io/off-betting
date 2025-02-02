<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BetSide;
use App\Enums\BetStatus;
use App\Enums\GameResult;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Bet extends Model
{
    use HasUuids;

    public $casts = [
        'bet_at' => 'datetime',
        'status' => BetStatus::class,
        'result' => GameResult::class,
        'side' => BetSide::class,
    ];

    public function eventGame(): BelongsTo
    {
        return $this->belongsTo(EventGame::class);
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
