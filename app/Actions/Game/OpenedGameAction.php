<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Events\SideOpenEvent;
use App\Livewire\Forms\OpenGameForm;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class OpenedGameAction
{
    /**
     * @throws Exception
     */
    public function handle(Event $event, OpenGameForm $gameForm): void
    {
        DB::transaction(function () use ($event, $gameForm): void {


            $game = $event->getCurrentGame();
            $game->wala_charge = $event->charge;
            $game->opened_at = now();
            $game->meron_entry = $gameForm->meron_name;
            $game->wala_entry = $gameForm->wala_name;
            $game->status = GameStatus::OPENED;
            $game->save();

            GameEvent::dispatch($game, null);
        });
    }
}
