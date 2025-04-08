<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

final class SideOpenEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $eventId) {}

    public function broadcastWith(): array
    {
        return [
            'open_meron' => Cache::get('open_meron', 1),
            'open_wala' => Cache::get('open_wala', 1),
        ];
    }

    public function broadcastAs(): string
    {
        return 'side-opened';
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('game-event.'.Event::getUuidInCache($this->eventId)),
        ];
    }
}
