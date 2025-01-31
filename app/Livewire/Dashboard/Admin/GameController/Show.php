<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Actions\Event\ClosedEventActions;
use App\Actions\Event\OpendEventActions;
use App\Actions\Game\ClosedGameActions;
use App\Actions\Game\DeclaredGameResultAction;
use App\Actions\Game\OpenedGameActions;
use App\Enums\GameResult;
use App\Http\Resources\EventGameResource;
use App\Livewire\Forms\OpenGameForm;
use App\Models\Event;
use App\Models\EventGame;
use Exception;
use Flux\Flux;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Component;

final class Show extends Component
{
    public Event $event;

    public  $game;

    public array $games = [];

    public OpenGameForm $gameForm;

    public $resultSelected;

    /**
     * @throws Exception
     */
    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->game = EventGameResource::make( $event->getCurrentGame())->resolve();

        $this->games = $event->games()->orderBy('game_number', 'asc')->get()->map(fn (EventGame $game): array => [
            'id' => $game->id,
            'game_number' => $game->game_number,
            'color' => $game->result->color(),
            'result' => $game->result->value,
        ])->toArray();

    }

    /**
     * @throws Exception
     */
    public function openEvent(OpendEventActions $action): void
    {
        $action->handle($this->event);

        Flux::toast('Event opened successfully', variant: 'success');

        Flux::modal('open-event')->close();
    }

    /**
     * @throws Exception
     */
    public function closeEvent(ClosedEventActions $action): RedirectResponse
    {
        $action->handle($this->event);

        Flux::toast('Event closed successfully', variant: 'success');

        Flux::modal('close-event')->close();

        return redirect()->route('events.game-controller');
    }

    /**
     * @throws Exception
     */
    public function openGame(OpenedGameActions $action): void
    {
        $this->gameForm->validate();
        $action->handle($this->event, $this->gameForm);
        Flux::toast('Game opened successfully', variant: 'success');
        Flux::modal('open-game')->close();
        $this->gameForm->reset();
    }

    public function closeGame(ClosedGameActions $action): void
    {
        $action->handle($this->event);
        Flux::modal('close-game')->close();
    }

    public  function  cancelledGameModal()
    {
        $this->resultSelected = 'cancelled';
        Flux::modal('game-result')->show();
    }



    public function declareGameResult(DeclaredGameResultAction $action): void
    {
        $result = GameResult::tryFrom($this->resultSelected);
        $action->handle($this->event,$result);
        Flux::toast('Game successfully declared', variant: 'success');
        Flux::modal('game-result')->close();
        $this->resultSelected = '';

    }


    public function render(): View
    {
        return view('livewire.dashboard.admin.game-controller.show');
    }
}
