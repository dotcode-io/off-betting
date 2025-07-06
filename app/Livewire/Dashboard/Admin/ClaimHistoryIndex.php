<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Enums\EventStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class ClaimHistoryIndex extends Component
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

    #[On('refreshClaimHistory')]
    public function refreshData(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Bet::query()
            ->with('eventGame', 'claimedBy', 'event')
            ->where('is_claimed', 1);

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        if ($this->from && $this->to) {
            $query->whereDate('claimed_at', '>=', $this->from)->whereDate('claimed_at', '<=', $this->to);
        }

        $query = $this->applySearch($query, ['reference_no', 'nickname']);
        $bets = $query->orderBy('claimed_at', 'desc')->paginate(10);

        return view('livewire.dashboard.admin.claim-history-index', [
            'bets' => $bets,
            'eventId' => $this->eventId,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
