<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class OpenedEventActions
{
    /**
     * @throws Exception
     */
    public function handle(Event $event): void
    {
        if ($event->status !== EventStatus::PENDING) {
            throw new Exception('Event is not pending');
        }

        DB::transaction(function () use ($event): void {
            $event->update([
                'opened_at' => now(),
                'status' => EventStatus::OPENED,
            ]);
            $event->createGames();
        });
    }
}
