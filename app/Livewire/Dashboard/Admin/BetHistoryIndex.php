<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Actions\CancelBetAction;
use App\Enums\EventStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Carbon\Carbon;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

final class BetHistoryIndex extends Component
{
    use Searchable, Sortable, WithPagination;

    public $events;

    public $eventId;

    public $from;

    public $to;

    public $betId;

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

    public function openModal(int $betId): void
    {

        $this->betId = $betId;

        Flux::modal('cancel-bet')->show();
    }

    /**
     * @throws Throwable
     */
    public function cancelBet(CancelBetAction $action): void
    {

        $action->handle($this->betId);

        $this->reset('betId');
        Flux::modal('cancel-bet')->close();

        Flux::toast(
            'Bet cancelled successfully',
            'success'
        );

    }

    public function render()
    {
        $query = Bet::query()->with('eventGame');

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        if ($this->from && $this->to) {
            $from = Carbon::parse($this->from);
            $to = Carbon::parse($this->to);
            $query->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()]);
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
