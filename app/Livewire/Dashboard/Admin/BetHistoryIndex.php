<?php

namespace App\Livewire\Dashboard\Admin;

use Livewire\Component;
use App\Models\Bet;
use App\Models\Event;
use Livewire\WithPagination;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use App\Enums\EventStatus;

class BetHistoryIndex extends Component
{
    use WithPagination, Searchable, Sortable;

    public $events;
    public $eventId;

    public $from;
    public $to;

    public function mount()
    {
        $this->eventId = Event::where('status', '!=', EventStatus::PENDING)->latest()->first()->id ?? null;
        $this->from = now()->subDays(7)->format('Y-m-d');
        $this->to = now()->format('Y-m-d');

        $this->events = Event::latest()->get();
    }

    public function getMatches(): array
    {
        return [
            'reference_no' => 'reference_no',
            'nickname' => 'nickname',
        ];
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Bet::query()->with('eventGame');

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        if ($this->from && $this->to) {
            $query->whereDate('created_at', '>=', $this->from)->whereDate('created_at', '<=', $this->to);
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->latest()->paginate(10);

        return view('livewire.dashboard.admin.bet-history-index', [
            'bets' => $bets,
            'eventId' => $this->eventId,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
