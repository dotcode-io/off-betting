<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Events\GameEvent;
use App\Jobs\DeclareResultJob;
use App\Models\Bet;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class DeclaredGameResultAction
{
    /**
     * @throws Exception
     */
    public function handle(Event $event, GameResult $result): void
    {
        if ($result === GameResult::PENDING) {
            throw new Exception('Game result is not valid');
        }
        DB::transaction(function () use ($event, $result): void {
            $game = $event->getCurrentGame();
            if ($game->status !== GameStatus::CLOSED) {
                throw new Exception('Game is not closed yet');
            }

            $game->done_at = now();
            $game->status = GameStatus::DONE;
            $game->result = $result;

            $game->save();

            $nextGame = $event->getCurrentGame();

            GameEvent::dispatch($game, $nextGame);
            DeclareResultJob::dispatch($game->id, $result, $game->getOdds());
        });
    }
}
