<?php

namespace App\Http\Controllers;

use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClaimController
{
    use Searchable;

    public function index(Request $request): JsonResponse
    {
        $query = Bet::query()->with('eventGame')->where('user_id', auth()->id());

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereDate('created_at', '>=', $request->get('from'))->whereDate('created_at', '<=', $request->get('to'));
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->where('claimed_by', auth()->id())->where('is_claimed', 1)->latest()->paginate(10);

        return response()->json([
            'bets' => $bets,
        ]);
    }
}
