<?php

namespace App\Livewire\Report;

use App\Events\GameEvent;
use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventReport extends Component
{
    use WithPagination;
    public Event $event;
    public function mount(Event $event)
    {
        $this->event = $event;
    }
    public function render()
    {

        return view('livewire.report.event-report');
    }

    public function games()
    {
        return GameEvent::query()

            ->where('event_id', $this->event->id)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
}
