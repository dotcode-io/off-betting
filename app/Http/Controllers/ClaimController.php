<?php

namespace App\Http\Controllers;

use App\Actions\ClaimTicketAction;
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
        $query = Bet::query()->with('eventGame', 'event')->where('user_id', auth()->id());

        if ($request->has('event_id')) {
            if (!empty($request->event_id)) {
                $query->where('event_id', $request->event_id);
            }
        }

        if ($request->has('from') && $request->has('to')) {
            if (!empty($request->from) && !empty($request->to)) {
                $query->whereDate('created_at', '>=', $request->get('from'))->whereDate('created_at', '<=', $request->get('to'));
            }
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->where('claimed_by', auth()->id())->where('is_claimed', 1)->latest()->paginate(10);

        return response()->json([
            'bets' => $bets,
        ]);
    }

    public function checkTicket(Request $request)
    {
        $request->validate([
            'ticket' => 'required|string'
        ]);

        $ticket = $request->get('ticket');
        $checkTicket = $this->validateTicket($ticket);

        info($checkTicket);

        if ($checkTicket['type'] === 'error') {
            return response()->json([
                'message' => $checkTicket['message']
            ], 400);
        }

        return response()->json([
            'message' => $checkTicket['message'],
            'bet' => $checkTicket['bet']
        ]);
    }

    public function claimTicket(ClaimTicketAction $actions, Request $request): JsonResponse
    {
        $request->validate([
            'ticket' => 'required|string'
        ]);

        $ticket = $request->get('ticket');
        $checkTicket = $this->validateTicket($ticket);

        if ($checkTicket['type'] === 'error') {
            return response()->json([
                'message' => $checkTicket['message']
            ], 400);
        }

        $bet = $checkTicket['bet'];
        $actions->handle($bet);

        return response()->json([
            'message' => 'Ticket claimed successfully!',
            'bet' => $bet
        ]);
    }

    private function validateTicket($ticket)
    {
        $bet = Bet::with('eventGame', 'event')->where('reference_no', $ticket)->first();

        if (! $bet) {
            return [
                'message' => 'Ticket not found!',
                'type' => 'error'
            ];
        }
        if ($bet->isOnGoing()) {
            return [
                'message' => 'This ticket is still on going!',
                'type' => 'error'
            ];
        }
        if ($bet->is_claimed) {
            return [
                'message' => 'Sorry, this ticket is already claimed!',
                'type' => 'error'
            ];
        }
        if ($bet->isLost()) {
            return [
                'message' => 'Sorry, this ticket is not a winner!',
                'type' => 'error'
            ];
        }

        if (!$bet->is_claimed && ($bet->isWin()) || $bet->isCancelled()) {
            return [
                'message' => 'Ticket found!',
                'type' => 'success',
                'bet' => $bet
            ];
        }
    }
}
