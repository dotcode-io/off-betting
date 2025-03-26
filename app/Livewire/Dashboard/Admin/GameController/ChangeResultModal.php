<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Enums\GameResult;
use App\Enums\GameStatus;
use App\Jobs\ChangeGameResultJob;
use App\Models\EventGame;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class ChangeResultModal extends Component
{
    public EventGame $game;

    public string $resultSelected = '';

    #[On('change-result')]
    public function openModal(int $id): void
    {
        $this->resultSelected = '';
        try {
            $this->game = EventGame::query()->where('status', GameStatus::DONE)->findOrFail($id);
        } catch (Throwable $e) {
            Flux::toast('Game not found!', variant: 'danger');

            return;
        }

        Flux::modal('change-result-modal')->show();
    }

    public function save(): void
    {
        $this->validate([
            'resultSelected' => ['required', 'in:meron,wala,draw,cancelled'],
        ]);

        $newResult = GameResult::tryFrom($this->resultSelected);

        if ($newResult === $this->game->result) {
            Flux::toast('Result is the same!', variant: 'warning');

            return;
        }

        $this->game->update([
            'result' => $newResult,
        ]);
        $this->game->result = $newResult;
        $this->dispatch('updateResult', $this->game);
        $this->resultSelected = '';

        ChangeGameResultJob::dispatch($this->game->id, $newResult);

        Flux::toast('Result successfully changed!', variant: 'success');
        Flux::modal('change-result-modal')->close();
    }

    public function render()
    {
        return view('livewire.dashboard.admin.game-controller.change-result-modal');
    }
}
