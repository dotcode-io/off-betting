<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Bet;
use App\Models\EventGame;
use App\Models\WalletLog;
use App\Enums\WalletLogType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DailyReportIndex extends Component
{
    public $selectedDate;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.dashboard.admin.daily-report-index');
    }

    #[\Livewire\Attributes\Computed]
    public function monthlyEarnings()
    {
        $startOfMonth = Carbon::parse($this->selectedDate)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedDate)->endOfMonth();

        return EventGame::query()
            ->selectRaw('
                SUM(earnings) as total_earnings,
                SUM(draw_earnings) as total_draw_earnings
            ')
            ->whereStatus('done')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function monthlyGhostbetEarnings()
    {
        $startOfMonth = Carbon::parse($this->selectedDate)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedDate)->endOfMonth();

        return Bet::query()
            ->selectRaw('SUM(gb_earnings) as total_win_amount')
            ->whereIn('user_id', config('app.gb_ids', []))
            ->whereIn('result', ['meron', 'wala'])
            ->whereBetween('bet_at', [$startOfMonth, $endOfMonth])
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function dailyEarnings()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        $startOfDay = $selectedDate->startOfDay();
        $endOfDay = $selectedDate->copy()->endOfDay();

        return EventGame::query()
            ->selectRaw('
                SUM(earnings) as total_earnings,
                SUM(draw_earnings) as total_draw_earnings
            ')
            ->whereStatus('done')
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function dailyGhostbetEarnings()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        $startOfDay = $selectedDate->startOfDay();
        $endOfDay = $selectedDate->copy()->endOfDay();

        return Bet::query()
            ->selectRaw('SUM(gb_earnings) as total_win_amount')
            ->whereIn('user_id', config('app.gb_ids', []))
            ->whereIn('result', ['meron', 'wala'])
            ->whereBetween('bet_at', [$startOfDay, $endOfDay])
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function dailyBetStats()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        $startOfDay = $selectedDate->startOfDay();
        $endOfDay = $selectedDate->copy()->endOfDay();

        return Bet::query()
            ->selectRaw('
                SUM(bet_amount) as total_bet_amount,
                COUNT(*) as total_bet_count
            ')
            ->whereBetween('bet_at', [$startOfDay, $endOfDay])
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function dailyWithdrawals()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        $startOfDay = $selectedDate->startOfDay();
        $endOfDay = $selectedDate->copy()->endOfDay();

        return Bet::query()
            ->selectRaw('SUM(win_amount) as total_win_amount)')
            ->where('is_claimed', true)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->first();
    }
}
