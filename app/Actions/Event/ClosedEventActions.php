<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\DB;

final class ClosedEventActions
{
    public function handle(Event $event): Event
    {
        if ($event->status !== EventStatus::OPENED) {
            throw new Exception('Event is not pending');
        }

        return DB::transaction(function () use ($event) {
            return $event->update([
                'status' => EventStatus::CLOSED,
            ]);
        });
    }
}
