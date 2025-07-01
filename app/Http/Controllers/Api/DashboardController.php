<?php

namespace App\Http\Controllers\Api;

use App\Enums\BetStatus;
use App\Models\Bet;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();

        // Optimize: Single query to get all bet statistics for today using direct model query
        $betStats = Bet::where('user_id', $user->id)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->selectRaw('
                COUNT(*) as bets_count,
                COALESCE(SUM(bet_amount), 0) as bets_amount,
                COALESCE(SUM(CASE WHEN status = ? THEN win_amount ELSE 0 END), 0) as wins_amount
            ', [BetStatus::Winner->value])
            ->first();

        // Optimize: Single query to get all claimed bet statistics for today
        $claimedStats = Bet::where('claimed_by', $user->id)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->where('is_claimed', true)
            ->selectRaw('
                COUNT(*) as claimed_count,
                COALESCE(SUM(win_amount), 0) as claimed_amount
            ')
            ->first();

        return response()->json([
            'message' => 'Welcome to the dashboard',
            'wallet' => number_format($user->wallet_amount, 2),
            'bets_today' => $betStats->bets_count ?? 0,
            'bets_today_amount' => number_format($betStats->bets_amount ?? 0, 2),
            'wins_today' => number_format($betStats->wins_amount ?? 0, 2),
            'claimed_bets_today' => $claimedStats->claimed_count ?? 0,
            'claimed_bets_today_amount' => number_format($claimedStats->claimed_amount ?? 0, 2),
        ], 200);
    }
}
