<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Enums\EventStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

final class ClaimHistoryIndex extends Component
{
    use Searchable, Sortable, WithPagination;

    public $events;

    public $eventId;

    public $from;

    public $to;

    public $voidClaimId;

    public $bet;

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

    public function openVoidModal(?Bet $bet): void
    {
        $this->voidClaimId = $bet->id;

        Flux::modal('void-claim')->show();
    }

    public function openBetDetailsModal(?Bet $bet): void
    {
        $this->bet = $bet;

        Flux::modal('bet-details')->show();
    }

    public function voidClaim()
    {
        $bet = Bet::findOrFail($this->voidClaimId);

        if ($bet) {
            $bet->update([
                'is_claimed' => 0,
                'claimed_by' => null,
                'claimed_at' => null,
            ]);

            Flux::modal('void-claim')->close();
            Flux::toast('Transaction voided successfully', variant: 'success');
            $this->resetPage();

            return response()->noContent();
        }

        return null;
    }

    public function render()
    {
        $query = Bet::query()
            ->with('eventGame', 'claimedBy')
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
