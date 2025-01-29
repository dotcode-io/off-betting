<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Enums\EventStatus;
use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public function render(): \Illuminate\View\View
    {

        $query = Event::query()->whereIn('status', [
            EventStatus::OPENED,
            EventStatus::PENDING,
        ])->latest();

        $events = $query->paginate(10);

        return view('livewire.dashboard.admin.game-controller.index', [
            'events' => $events,
        ]);
    }
}
