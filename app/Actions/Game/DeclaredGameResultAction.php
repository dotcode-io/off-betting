<?php

namespace App\Actions\Game;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class DeclaredGameResultAction
{

    /**
     * @throws \Exception
     */
    public  function handle(Event $event, GameResult $result): void
    {
        if($result === GameResult::PENDING){
            throw new \Exception('Game result is not valid');
        }
        DB::transaction(function () use ($event,$result) {
            $game = $event->getCurrentGame();
            $game->done_at = now();
            $game->status = GameStatus::DONE;
            $game->result = $result;
            $game->save();


            $nextGame = $event->getCurrentGame();

            GameEvent::dispatch($game,$nextGame);
        });
    }
}
