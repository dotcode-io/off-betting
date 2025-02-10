<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class OpenedEventAction
{
    /**
     * @throws Exception
     */
    public function handle(Event $event): Event
    {
        if ($event->status !== EventStatus::PENDING) {
            throw new Exception('Event is not pending');
        }

        return DB::transaction(function () use ($event): Event {
            $event->opened_at = now();
            $event->status = EventStatus::OPENED;
            $event->save();
            $event->createGames();

            return $event;
        });
    }
}
