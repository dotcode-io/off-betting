<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Actions\Event\ClosedEventAction;
use App\Actions\Event\OpenedEventAction;
use App\Actions\Game\ClosedGameAction;
use App\Actions\Game\DeclaredGameResultAction;
use App\Actions\Game\OpenedGameAction;
use App\Enums\EventStatus;
use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Http\Resources\EventGameResource;
use App\Livewire\Forms\OpenGameForm;
use App\Models\Event;
use App\Models\EventGame;
use Exception;
use Flux\Flux;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

final class Show extends Component
{
    public Event $event;

    public $game;

    public array $games = [];

    public OpenGameForm $gameForm;

    public $resultSelected;

    public $rankings = [];

    /**
     * @throws Exception
     */
    public function mount(Event $event): void
    {
        $this->event = $event;

        if ($this->event->status === EventStatus::OPENED) {
            $this->getGames();
        }
    }

    /**
     * @throws Exception
     */
    public function getGames(): void
    {
        $currentGame = $this->event->getCurrentGame();
        $game = EventGameResource::make($currentGame)->resolve();
        $this->game = $game;
        if ($currentGame->status === GameStatus::OPENED) {
            $this->rankings = $currentGame->getRankings();
        }

        $this->games = $this->event->games()->orderBy('game_number', 'asc')->get()->map(fn (EventGame $game): array => [
            'id' => $game->id,
            'game_number' => $game->game_number,
            'color' => $game->result->color(),
            'result' => $game->result->value,
        ])->toArray();
    }

    #[On('event-opened')]
    public function eventOpened(): void
    {
        $this->getGames();
    }

    #[On('game-updated')]
    public function refreshGame(): void
    {
        $this->game = EventGameResource::make($this->event->getCurrentGame())->resolve();
    }

    #[On('updateResult')]
    public function updateResult(EventGame $eventGame): void
    {
        $this->games = array_map(function ($game) use ($eventGame) {
            if ($game['id'] === $eventGame->id) {
                $game['color'] = $eventGame->result->color();
                $game['result'] = $eventGame->result->value;
            }

            return $game;
        }, $this->games);
    }

    /**
     * @throws Exception
     */
    public function openEvent(OpenedEventAction $action): void
    {
        $this->event = $action->handle($this->event);

        Flux::toast('Event opened successfully', variant: 'success');

        Flux::modal('open-event')->close();

        $this->getGames();
    }

    /**
     * @throws Exception
     */
    public function closeEvent(ClosedEventAction $action): Redirector
    {
        $action->handle($this->event);

        Flux::toast('Event closed successfully', variant: 'success');

        Flux::modal('close-event')->close();

        return redirect()->route('events.game-controller');
    }

    /**
     * @throws Exception
     */
    public function openGame(OpenedGameAction $action): void
    {
        $action->handle($this->event, $this->gameForm);

        $this->dispatch('game-updated');

        Flux::toast('Game opened successfully', variant: 'success');
        Flux::modal('open-game')->close();
        $this->gameForm->reset();
    }

    public function closeGame(ClosedGameAction $action): void
    {
        $action->handle($this->event);
        $this->dispatch('game-updated');

        Flux::toast('Game closed successfully', variant: 'success');
        Flux::modal('close-game')->close();
    }

    public function cancelledGameModal(): void
    {
        $this->resultSelected = 'cancelled';
        Flux::modal('game-result')->show();
    }

    /**
     * @throws Exception
     */
    public function declareGameResult(DeclaredGameResultAction $action): void
    {
        $result = GameResult::tryFrom($this->resultSelected);
        $action->handle($this->event, $result);

        $this->getGames();

        Flux::toast('Game successfully declared', variant: 'success');
        Flux::modal('game-result')->close();
        $this->resultSelected = '';

    }

    public function render(): View
    {
        return view('livewire.dashboard.admin.game-controller.show')->title('Game Controller');
    }
}
