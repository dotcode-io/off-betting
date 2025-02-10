<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

final class RecentBetController
{
    public function show(Event $event): JsonResponse
    {
        $bets = Bet::query()
            ->with(['eventGame'])
            ->where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json([
            'event' => $event,
            'bets' => $bets,
        ], 200);
    }
}
