<?php

declare(strict_types=1);

namespace App\Livewire\GameViewer;

use App\Enums\EventStatus;
use App\Http\Resources\EventGameResource;
use App\Models\Event;
use App\Models\EventGame;
use Livewire\Component;

final class Index extends Component
{
    public Event $event;

    public $game;

    public array $games = [];

    public array $gameResults = [];

    public function mount(): void
    {
        $this->event = Event::query()
            ->where('status', EventStatus::OPENED)->latest()->firstOrFail();
        $this->getGames();


    }

    public function getGames(): void
    {
        $currentGame = $this->event->getCurrentGame();
        $game = EventGameResource::make($currentGame)->resolve();
        $this->game = $game;

        $this->games = $this->event->games()->orderBy('game_number', 'asc')->get()->map(fn (EventGame $game): array => [
            'id' => $game->id,
            'game_number' => $game->game_number,
            'color' => $game->result->color(),
            'result' => $game->result->value,
        ])->toArray();

        $this->gameResults = $this->event->games()->where('status', 'done')->orderBy('game_number', 'desc')->get()->map(fn (EventGame $game): array => [
            'id' => $game->id,
            'game_number' => $game->game_number,
            'color' => $game->result->color(),
            'result' => $game->result->value,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.game-viewer.index', [
            'events' => $this->event,
            'games' => $this->games,
            'game' => $this->game,
            'gameResults' => $this->gameResults,
        ])->layout('components.game-viewer-layout')->title('Game Viewer');
    }
}
