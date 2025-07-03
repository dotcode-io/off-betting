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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

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
            $this->open_meron = Cache::get('open_meron', 0);
            $this->open_wala = Cache::get('open_wala', 0);
        }

        // Generate initial idempotency key
        $this->betForm->generateIdempotencyKey();
    }

    public function setSide(string $side): void {}

    public function submitBet(BetAction $actions): void
    {
        $this->betForm->side = $this->side;
        $this->betForm->validate();
        $amount = (float) str_replace(',', '', (string) $this->betForm->amount);

        $userId = Auth::id();
        $lockKey = "user_bet_lock_{$userId}";

        // Acquire lock for 30 seconds to prevent concurrent requests
        $lock = Cache::lock($lockKey, 30);

        try {
            if (! $lock->get()) {
                $this->addError('betForm.amount', 'Another betting request is currently being processed. Please wait and try again.');
                return;
            }

            $bet = $actions->handle($this->event, BettingDataTransferObject::fromArray([
                'amount' => $amount,
                'side' => $this->betForm->side,
            ]), null, $this->betForm->idempotency_key);

            $bet->load(['eventGame']);
            $this->betForm->reset();
            $this->side = '';

            // Generate new idempotency key for next bet
            $this->betForm->generateIdempotencyKey();

            $this->betToPrint = $bet;

            Flux::toast('Bet placed successfully! Please wait the receipt', variant: 'success');

            Flux::modal('print-bet')->show();

            $this->dispatch('bet-placed');
        } catch (Throwable $ex) {
            $this->addError('betForm.amount', $ex->getMessage());
        } finally {
            // Always release the lock, whether success or failure
            $lock->release();
        }
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
