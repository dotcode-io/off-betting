<?php

namespace App\Events;

use App\Http\Resources\EventGameResource;
use App\Models\Event;
use App\Models\EventGame;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GameEvent implements  ShouldBroadcastNow
{
    use Dispatchable;

    public function __construct(public EventGame $currentGame, public ?EventGame $nextGame)
    {
    }

    public function broadcastWith(): array
    {
        return [
            'current' => new EventGameResource($this->currentGame),
            'next' => $this->nextGame ? new EventGameResource($this->nextGame) : null,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game-event.'.Event::getUuidInCache($this->currentGame->event_id)),
        ];
    }
}
