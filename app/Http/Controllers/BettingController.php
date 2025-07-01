<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CancelBetAction;
use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(BetAction $actions, Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/',
            'side' => 'required', 'string', 'in:meron,wala,draw',
            'has_printer' => 'required|boolean',
            'ref' => [
                'required_if:has_printer,false',
                'string',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('manual_refs')
                        ->where('ref', $value)
                        ->where('used', false)
                        ->exists();

                    if (! $exists) {
                        $fail('The reference is either invalid or already used.');
                    }
                },
            ],

        ]);
        $event = Event::getCurrent();
        $bet = $actions->handle($event, BettingDataTransferObject::fromArray($request->only('amount', 'side')), $request->ref);
        $bet->load('eventGame', 'event');

        return response()->json([
            'message' => 'Bet placed successfully',
            'has_printer' => $request->has_printer,
            'ref' => $request->has_printer ? $request->ref : null,
            'bet' => $bet,
        ], 201);
    }

    public function cancel(CancelBetAction $action, Request $request): JsonResponse
    {
        $request->validate([
            'bet_id' => 'required|integer|exists:bets,id',
        ]);

        try {
            $action->handle($request->bet_id);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => 'Bet cancelled successfully'], 200);
    }
}
