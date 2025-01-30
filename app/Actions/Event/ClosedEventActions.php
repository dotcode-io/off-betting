<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class ClosedEventActions
{
    public function handle(Event $event): void
    {
        if ($event->status !== EventStatus::OPENED) {
            throw new Exception('Event is not pending');
        }

        DB::transaction(function () use ($event): void {
            $event->update([
                'closed_at' => now(),
                'status' => EventStatus::CLOSED,
            ]);
        });
    }
}
