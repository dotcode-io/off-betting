<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Modals;

use App\Models\Bet;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class BetDetailsModal extends Component
{
    public Bet $bet;

    #[On('bet-details')]
    public function openModal(int $betId): void
    {
        try {
            $this->bet = Bet::with('eventGame', 'claimedBy', 'event')->findOrFail($betId);
        } catch (Throwable $e) {
            Flux::toast('Bet not found!', variant: 'danger');

            return;
        }

        Flux::modal('bet-details-modal')->show();
    }

    public function render()
    {
        return view('livewire.dashboard.modals.bet-details-modal');
    }
}
