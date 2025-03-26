<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BettingController
{
    use Searchable;

    public function index(Request $request): JsonResponse
    {
        $query = Bet::query()->with('eventGame', 'event')->where('user_id', auth()->id());

        if ($request->has('event_id')) {
            if (!is_null($request->event_id)) {
                $query->where('event_id', $request->event_id);
            }
        }

        if ($request->has('from') && $request->has('to')) {
            if (!empty($request->from) && !empty($request->to)) {
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
        ]);
        $event = Event::getCurrent();
        $bet = $actions->handle($event, BettingDataTransferObject::fromArray($request->only('amount', 'side')));
        $bet->load('eventGame', 'event');

        return response()->json([
            'message' => 'Bet placed successfully',
            'bet' => $bet
        ]);
    }
}
