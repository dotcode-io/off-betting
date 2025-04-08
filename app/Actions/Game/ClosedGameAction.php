<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Events\SideOpenEvent;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class ClosedGameAction
{
    public function handle(Event $event): void
    {
        DB::transaction(function () use ($event): void {

            // Bet sum case when side meron,wala,draw then sum amount else 0 end as total_bets


            Cache::put('open_meron', 0);
            Cache::put('open_wala', 0);

            SideOpenEvent::dispatch($event->uuid);
            $game = $event->getCurrentGame();

            $game->closed_at = now();
            $game->status = GameStatus::CLOSED;
            $game->save();

            GameEvent::dispatch($game, null);
        });
    }
}
