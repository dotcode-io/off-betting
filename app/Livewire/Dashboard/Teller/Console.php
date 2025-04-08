<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Enums\EventStatus;
use App\Http\Resources\EventGameResource;
use App\Livewire\Forms\BetForm;
use App\Models\Bet;
use App\Models\Event;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Cache;
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

    public $open_meron = 0;

    public $open_wala = 0;

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $event = Event::query()->where('status', EventStatus::OPENED)->latest()->first();

        if ($event) {
            $this->event = $event;
            $this->game = EventGameResource::make($event->getCurrentGame())->resolve();
            $this->open_meron = Cache::get('open_meron', 1);
            $this->open_wala = Cache::get('open_wala', 1);
        }

    }

    public function setSide(string $side): void {}

    public function submitBet(BetAction $actions): void
    {
        $this->betForm->side = $this->side;
        $this->betForm->validate();
        $amount = (float) str_replace(',', '', $this->betForm->amount);

        $bet = $actions->handle($this->event, BettingDataTransferObject::fromArray([
            'amount' => $amount,
            'side' => $this->betForm->side,
        ]), null);
        $bet->load(['eventGame']);
        $this->betForm->reset();
        $this->side = '';

        $this->betToPrint = $bet;

        Flux::toast('Bet placed successfully! Please wait the receipt', variant: 'success');

        Flux::modal('print-bet')->show();

        $this->dispatch('bet-placed');
    }

    #[On('reprint-bet')]
    public function reprintBet(Bet $bet): void
    {
        $this->betToPrint = $bet->load(['eventGame', 'user', 'event']);
        Flux::modal('print-bet')->show();

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
