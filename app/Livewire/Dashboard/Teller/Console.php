<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Actions\Console\BetActions;
use App\Enums\EventStatus;
use App\Http\Resources\EventGameResource;
use App\Livewire\Forms\BetForm;
use App\Models\Bet;
use App\Models\Event;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

final class Console extends Component
{
    public BetForm $betForm;

    public $game;

    public $side;

    public Event $event;

    public $betToPrint;

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $event = Event::query()->where('status', EventStatus::OPENED)->first();

        if ($event) {
            $this->event = $event;
            $this->game = EventGameResource::make($event->getCurrentGame())->resolve();
        }

    }

    public function setSide(string $side): void {}

    public function submitBet(BetActions $actions): void
    {
        $this->betForm->side = $this->side;
        $this->betForm->validate();
        $bet = $actions->handle($this->event, $this->betForm);
        $bet->load(['eventGame', 'user', 'event']);
        $this->betForm->reset();
        $this->side = '';

        $this->betToPrint = $bet;

        Flux::toast('Bet placed successfully! Please wait the receipt', variant: 'success');

        Flux::modal('print-bet')->show();

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

        $this->dispatch('bet-placed');
    }

    #[On('reprint-bet')]
    public function reprintBet(Bet $bet): void
    {
        $this->betToPrint = $bet->load(['eventGame', 'user', 'event']);
        Flux::modal('print-bet')->show();
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

    public function printBet(): void
    {
        Flux::modal('print-bet')->close();
        $this->dispatch('bet-to-print');
    }

    public function render(): View
    {
        return view('livewire.dashboard.teller.console', [
            'event' => $this->event ?? null,
            'game' => $this->game,
            'side' => $this->side,
        ]);
    }
}
