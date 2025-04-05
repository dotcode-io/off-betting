<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Bet;
use Illuminate\Support\Facades\Cache;

final class BetReferenceService
{
    public function generate(int $eventId, int $gameId): string
    {
        $cacheKey = "bet_sequence:{$eventId}:{$gameId}";

        // Initialize sequence if it doesn't exist in cache
        if (! Cache::has($cacheKey)) {
            $lastBet = Bet::where('event_id', $eventId)
                ->where('event_game_id', $gameId)
                ->orderByDesc('id')
                ->first();

            $lastSequence = $lastBet
                ? $this->extractSequence($lastBet->reference_no)
                : 0;

            Cache::put($cacheKey, $lastSequence);
        }

        $sequence = Cache::increment($cacheKey);

        return "{$eventId}-{$gameId}-".mb_str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
    }

    private function extractSequence(string $referenceNo): int
    {
        // Format: eventId-gameId-seqNumber
        $parts = explode('-', $referenceNo);
        return isset($parts[2]) ? (int) mb_ltrim($parts[2], '0') : 0;
    }
}
