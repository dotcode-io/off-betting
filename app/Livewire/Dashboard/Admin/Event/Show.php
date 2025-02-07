<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\Event;

use App\Models\Event;
use App\Models\EventGame;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Livewire\Component;
use Livewire\WithPagination;

final class Show extends Component
{
    use Searchable, Sortable, WithPagination;

    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event->load('games');
    }

    public function render()
    {
        $games = EventGame::query()
            ->where('event_id', $this->event->id)
            ->paginate(10);

        $results = EventGame::where('event_id', $this->event->id)
            ->selectRaw('result, COUNT(*) as count')
            ->groupBy('result')
            ->pluck('count', 'result');

        return view('livewire.dashboard.admin.event.show', [
            'event' => $this->event,
            'games' => $games,
            'meron' => $results['meron'] ?? 0,
            'wala' => $results['wala'] ?? 0,
            'draw' => $results['draw'] ?? 0,
            'cancelled' => $results['cancelled'] ?? 0,
        ])->title('Event Details');
    }
}
