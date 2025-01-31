<?php

namespace App\Actions\Game;

use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class ClosedGameActions
{

    public  function handle(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $game = $event->getCurrentGame();
            $game->closed_at = now();
            $game->status = GameStatus::CLOSED;
            $game->save();

            GameEvent::dispatch($game,null);
        });
    }
}
