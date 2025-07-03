<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CancelBetAction;
use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Http\Requests\BetRequest;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

final class BettingController
{
    use Searchable;

    public function index(Request $request): JsonResponse
    {
        $query = Bet::query()->with('eventGame', 'event')->where('user_id', auth()->id());

        if ($request->has('event_id')) {
            if (! is_null($request->event_id)) {
                $query->where('event_id', $request->event_id);
            }
        }

        if ($request->has('from') && $request->has('to')) {
            if (! empty($request->from) && ! empty($request->to)) {
                $query->whereDate('bet_at', '>=', $request->from)->whereDate('bet_at', '<=', $request->to);
            }
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->latest()->paginate(10);

        return response()->json([
            'bets' => $bets,
        ]);
    }

    public function store(BetAction $actions, BetRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $lockKey = "user_bet_lock_{$userId}";

        // Acquire lock for 30 seconds to prevent concurrent requests
        $lock = Cache::lock($lockKey, 30);

        try {
            if (! $lock->get()) {
                return response()->json([
                    'message' => 'Another betting request is currently being processed. Please wait and try again.',
                    'error' => 'concurrent_request',
                ], 429);
            }

            $event = Event::getCurrent();
            $bet = $actions->handle($event, BettingDataTransferObject::fromArray($request->only('amount', 'side')), $request->ref, $request->idempotency_key);
            $bet->load('eventGame', 'event');

            return response()->json([
                'message' => 'Bet placed successfully',
                'has_printer' => $request->has_printer,
                'ref' => $request->has_printer ? $request->ref : null,
                'bet' => $bet,
            ], 201);

        } catch (Throwable $e) {
            // Re-throw the exception to be handled by Laravel's exception handler
            throw $e;
        } finally {
            // Always release the lock, whether success or failure
            $lock->release();
        }
    }

    public function cancel(CancelBetAction $action, Request $request): JsonResponse
    {
        $request->validate([
            'bet_id' => 'required|integer|exists:bets,id',
        ]);

        try {
            $action->handle($request->bet_id);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => 'Bet cancelled successfully'], 200);
    }
}
