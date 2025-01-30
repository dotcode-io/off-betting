<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Actions\Event\ClosedEventActions;
use App\Actions\Event\OpendEventActions;
use App\Models\Event;
use App\Models\EventGame;
use Flux\Flux;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public Event $event;

    public array $games = [];

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->games = $event->games()->orderBy('game_number', 'asc')->get()->map(fn(EventGame $game): array => [
            'id' => $game->id,
            'game_number' => $game->game_number,
            'color' => $game->result->color(),
            'result' => $game->result->value,
        ])->toArray();

    }

    /**
     * @throws \Exception
     */
    public function openEvent(OpendEventActions $action): void
    {
        $action->handle($this->event);

        Flux::toast('Event opened successfully', variant: 'success');

        Flux::modal('open-event')->close();
    }

    /**
     * @throws \Exception
     */
    public function closeEvent(ClosedEventActions $action): RedirectResponse
    {
        $action->handle($this->event);

        Flux::toast('Event closed successfully', variant: 'success');

        Flux::modal('close-event')->close();

        return redirect()->route('events.game-controller');
    }

    public function render(): View
    {
        return view('livewire.dashboard.admin.game-controller.show');
    }
}
