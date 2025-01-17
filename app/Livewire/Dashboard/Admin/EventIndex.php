<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Models\Event;
use Livewire\Component;

final class EventIndex extends Component
{
    public function render(): \Illuminate\View\View
    {
        $events = Event::get();

        return view('livewire.dashboard.admin.event-index', [
            'events' => $events,
        ]);
    }
}
