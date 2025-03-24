<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\EventGameResource;
use App\Models\Event;
use App\Models\EventGame;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

final class GameEvent implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(public EventGame $currentGame, public ?EventGame $nextGame) {}

    public function broadcastWith(): array
    {
        return [
            'current' => new EventGameResource($this->currentGame),
            'next' => $this->nextGame instanceof EventGame ? new EventGameResource($this->nextGame) : null,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('game-event.'.Event::getUuidInCache($this->currentGame->event_id)),
        ];
    }

    public function broadcastAs(): string
    {
        return 'game-event';
    }
}
