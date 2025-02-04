<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Controller;

use App\Enums\EventStatus;
use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

final class EventIndex extends Component
{
    use WithPagination;

    public function render()
    {
        $query = Event::query()->whereIn('status', [
            EventStatus::OPENED,
            EventStatus::PENDING,
        ])->latest();

        $events = $query->paginate(10);

        return view('livewire.dashboard.controller.event-index', [
            'events' => $events,
        ])->title('Game Controller');
    }
}
