<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Models\EventGame;

final class UpdateBetStatusAction
{
    public function handle(int $gameId, array $attributes): void
    {
        $game = EventGame::query()
            ->where('status', GameStatus::OPENED)
            ->findOrFail($gameId);

        $game->update($attributes);

        GameEvent::dispatch($game, null);
    }
}
