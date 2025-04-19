<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Actions\ClaimTicketAction;
use App\Models\Bet;
use Flux\Flux;
use Livewire\Component;

final class ClaimTicket extends Component
{
    public $reference_no = '';

    public $bet;

    public function checkTicket(): void
    {
        $bet = Bet::where('reference_no', $this->reference_no)->first();

        if (! $bet) {
            Flux::toast(heading: 'Ticket not found!', text: "The reference you entered can't be found!", variant: 'warning');

            return;
        }
        if ($bet->isOnGoing()) {
            Flux::toast(heading: 'Game On-Going', text: 'The game is still on-going. Please try again later.', variant: 'warning');

            return;
        }
        if ($bet->is_claimed) {
            Flux::toast(heading: 'Already claimed', text: 'This ticket is already claimed.', variant: 'warning');

            return;
        }
        if ($bet->isLost()) {
            Flux::toast(heading: 'Bet lost', text: 'Sorry, this ticket is not a winner!', variant: 'warning');

            return;
        }
        if ($bet->is_claimed === 0 && $bet->isWin()) {
            $this->bet = $bet;

            return;
        }
    }

    public function close(): void
    {
        $this->bet = null;
        $this->reference_no = '';
    }

    public function claim(ClaimTicketAction $action)
    {
        if ($this->bet) {
            $action->handle($this->bet);
            Flux::toast(heading: 'Congratulations!', text: 'Ticket claimed successfully!', variant: 'success');

            // $this->bet = null;
            // $this->reference_no = '';

            Flux::modal('print-bet')->show();
        }
        else{
            Flux::toast(heading: 'Error', text: 'Something went wrong!', variant: 'danger');
        }

        // return redirect()->route('teller.claim-history');


    }

    public function reprint()
    {
        Flux::modal('print-bet')->show();
    }

    public function printBet(): void
    {
        Flux::modal('print-bet')->close();
        $this->dispatch('bet-to-print');
    }

    public function render()
    {
        return view('livewire.dashboard.teller.claim-ticket');
    }
}
