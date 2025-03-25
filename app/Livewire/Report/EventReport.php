<?php

namespace App\Livewire\Report;

use App\Models\Event;
use App\Models\EventGame;
use Livewire\Component;
use Livewire\WithPagination;

class EventReport extends Component
{
    use WithPagination;
    public Event $event;
    public function mount(Event $event)
    {
        $this->event = $event;
        $this->resetPage();
    }
    public function render()
    {

        return view('livewire.report.event-report');
    }

    #[\Livewire\Attributes\Computed]
    public function totals()
    {
        return EventGame::query()
            ->selectRaw('
                SUM(earnings) as total_earnings,
                SUM(draw_earnings) as total_draw_earnings
            ')
            ->where('event_id', $this->event->id)
            ->whereStatus('done')
            ->first();
    }

    #[\Livewire\Attributes\Computed]
    public function games()
    {
        return EventGame::query()
            ->where('event_id', $this->event->id)
            ->orderBy('game_number', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10);
    }
}
