<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Models\Bet;
use App\Models\Event;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class RecentBets extends Component
{
    use WithPagination;

    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    #[On('bet-placed')]
    public function refresh(): void {}

    public function reprintReceipt(Bet $bet): void
    {
        Flux::toast('Reprinting receipt! Please wait!', variant: 'success');

        $this->dispatch('reprint-bet', $bet);
        // $this->dispatch('silent-print', [
        //     'printData' => [
        //         'event' => $this->event->name,
        //         'fight' => $bet->eventGame->game_number,
        //         'side' => $bet->side->label(),
        //         'amount' => number_format((float) $bet->bet_amount, 2, '.', ','),
        //         'ref' => $bet->reference_no,
        //         'teller' => Auth::user()->username,
        //         'date' => $bet->bet_at->format('F d, Y'),
        //         'time' => $bet->bet_at->format('H:i A'),
        //     ],
        // ]);
    }

    public function render()
    {
        $bets = Bet::query()
            ->with(['eventGame'])
            ->where('event_id', $this->event->id)
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.dashboard.teller.recent-bets', [
            'bets' => $bets,
        ]);
    }
}
