<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventGame;
use Exception;
use Illuminate\Support\Facades\DB;

final class ClosedEventAction
{
    /**
     * @throws Exception
     */
    public function handle(Event $event): void
    {
        if ($event->status !== EventStatus::OPENED) {
            throw new Exception('Event is not pending');
        }

        DB::transaction(function () use ($event): void {

            $earnings = EventGame::query()
                ->selectRaw('
                SUM(earnings) as total_earnings,
                SUM(draw_earnings) as total_draw_earnings
            ')
                ->where('event_id', $event->id)
                ->whereStatus('done')
                ->first();

            $event->update([
                'closed_at' => now(),
                'status' => EventStatus::CLOSED,
                'total_earnings' => $earnings->total_earnings,
                'total_draw_earnings' => $earnings->total_draw_earnings,
            ]);
        });
    }
}
