<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\GameResult;
use App\Models\Bet;
use App\Models\Event;
use App\Models\Report;
use Exception;

final class CreateReportAction
{
    /**
     * @throws Exception
     */
    public function handle(Event $event)
    {
        if (! $event->status->isClosed()) {
            throw new Exception('Event is not closed');
        }

        $report = Report::where('date', $event->date)->first();

        if (is_null($report)) {

            $currentMonth = date('m');

            $gb_month_bet_earning = Report::query()
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $event->date->year)
                ->sum('daily_gb_earning');

            $gb_earning_data = Bet::query()->select(['SUM(bet_amount) as bet_amount', 'SUM(win_amount) as win_amount'])
                ->where('event_id', $event->id)
                ->where('user_id', 2)
                ->whereIn('result', [GameResult::WALA, GameResult::MERON])
                ->first();



            $dailyGbEarning = $gb_earning_data->win_amount - $gb_earning_data->bet_amount;
            $currentMonthGbEarning = $gb_month_bet_earning;
            $dailyEarning = 0;
            $currentMonthEarning = 0;
            $dailyTotalBet = 0;
            $dailyTotalWithdrawal = 0;

            $report = Report::query()->create([
                'date' => $event->date,
                'daily_gb_earning' => $dailyGbEarning,
                'current_month_gb_earning' => $currentMonthGbEarning + $dailyGbEarning,
                'daily_earning' => $dailyEarning,
                'current_month_earning' => $currentMonthEarning,
                'daily_total_bet' => $dailyTotalBet,
                'daily_total_withdrawal' => $dailyTotalWithdrawal,
                'event_id' => $event->id,
            ]);
        }

    }
}
