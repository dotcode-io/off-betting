<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin\GameController;

use App\Actions\Event\ClosedEventActions;
use App\Actions\Event\OpendEventActions;
use App\Models\Event;
use Flux\Flux;
use Livewire\Component;

final class Show extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function openEvent(OpendEventActions $action)
    {
        $action->handle($this->event);

        Flux::toast('Event opened successfully', variant: 'success');

        Flux::modal('open-event')->close();
    }

    public function closeEvent(ClosedEventActions $action)
    {
        $action->handle($this->event);

        Flux::toast('Event closed successfully', variant: 'success');

        Flux::modal('close-event')->close();

        return redirect()->route('events.game-controller');
    }

    public function render()
    {
        return view('livewire.dashboard.admin.game-controller.show');
    }
}
