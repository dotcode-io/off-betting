<?php

declare(strict_types=1);

namespace App\Livewire\Report;

use App\Models\Bet;
use App\Models\Event;
use Livewire\Component;

final class GbReport extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.report.gb-report');
    }

    #[\Livewire\Attributes\Computed]
    public function data()
    {
        return Bet::query()
            ->with(['user', 'eventGame' => function ($query) {
                $query->select('id', 'plasada', 'game_number');
            }])
            ->selectRaw("
                user_id,
                event_game_id,
                SUM(CASE WHEN side = 'meron' THEN bet_amount ELSE 0 END) as meron_amount,
                SUM(CASE WHEN side = 'wala' THEN bet_amount ELSE 0 END) as wala_amount,
                SUM(CASE WHEN side = 'meron' THEN win_amount ELSE 0 END) as meron_win_amount,
                SUM(CASE WHEN side = 'wala' THEN win_amount ELSE 0 END) as wala_win_amount,
                SUM(win_amount) as total_win_amount
            ")
            ->whereIn('user_id', config('app.gb_ids'))
            ->whereIn('result', ['meron', 'wala'])
            ->where('event_id', $this->event->id)
            ->groupBy('user_id', 'event_game_id')
            ->orderBy('event_game_id', 'asc')
            ->orderBy('user_id', 'asc')
            ->get();

    }
}
