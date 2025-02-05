<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Enums\EventStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Livewire\Component;
use Livewire\WithPagination;

final class BetHistory extends Component
{
    use Searchable, Sortable, WithPagination;

    public $events;

    public $eventId;

    public $from;

    public $to;

    public function mount(): void
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

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Bet::query()->with('eventGame')->where('user_id', auth()->id());

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        if ($this->from && $this->to) {
            $query->whereDate('created_at', '>=', $this->from)->whereDate('created_at', '<=', $this->to);
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->latest()->paginate(10);

        return view('livewire.dashboard.teller.bet-history', [
            'bets' => $bets,
            'eventId' => $this->eventId,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
