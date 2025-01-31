<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Actions\Console\BetActions;
use App\Enums\EventStatus;
use App\Http\Resources\EventGameResource;
use App\Livewire\Forms\BetForm;
use App\Models\Event;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

final class Console extends Component
{
    public BetForm $betForm;

    public $game;

    public Event $event;

    /**
     * @throws Exception
     */
    public function mount(Event $event): void
    {
        if ($event->status !== EventStatus::OPENED) {
            abort(404);
        }
        $this->event = $event;
        $this->game = EventGameResource::make($event->getCurrentGame())->resolve();

    }

    public function setSide(string $side): void
    {
        $this->betForm->side = $side;
    }

    public function submitBet(BetActions $actions): void
    {
        $this->betForm->validate();
        $bet = $actions->handle($this->event, $this->betForm);
        $bet->load(['eventGame']);
        $this->betForm->reset();

        Flux::toast('Bet placed successfully! Please wait the receipt', variant: 'success');

        $this->dispatch('silent-print', [
            'printData' => [
                'event' => $this->event->name,
                'fight' => $bet->eventGame->game_number,
                'side' => $bet->side->label(),
                'amount' => number_format($bet->bet_amount, 2, '.', ','),
                'ref' => $bet->uuid,
                'teller' => Auth::user()->username,
                'date' => $bet->bet_at->format('F d, Y'),
                'time' => $bet->bet_at->format('H:i A'),
            ],
        ]);
    }

    public function render(): View
    {
        return view('livewire.dashboard.teller.console');
    }
}
