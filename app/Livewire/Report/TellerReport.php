<?php

declare(strict_types=1);

namespace App\Livewire\Report;

use App\Models\Bet;
use App\Models\Event;
use Livewire\Component;

final class TellerReport extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.report.teller-report');
    }

    #[\Livewire\Attributes\Computed]
    public function data()
    {
        // Bet sum bet amount and win_amount and sum amount if is_claim  group by user_id
        return Bet::query()
            ->with('user')
            ->selectRaw('
                user_id,
                SUM(bet_amount) as total_amount,
                SUM(win_amount) as total_win_amount,
                SUM(CASE WHEN is_claimed THEN win_amount ELSE 0 END) as total_claim_amount
            ')
            ->where('event_id', $this->event->id)
            ->whereNotIn('user_id', config('app.gb_ids'))
            ->groupBy('user_id')
            ->orderBy('user_id', 'asc')
            ->get();

    }
}
