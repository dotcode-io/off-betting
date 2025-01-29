<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Admin;

use App\Actions\Event\ClosedEventActions;
use App\Actions\Event\OpendEventActions;
use App\Actions\Event\UpsertEventActions;
use App\Livewire\Forms\EventForm;
use App\Models\Event;
use App\Traits\Table\Searchable;
use App\Traits\Table\Sortable;
use Flux\Flux;
use Illuminate\Http\Response;
use Livewire\Component;
use Livewire\WithPagination;

final class EventIndex extends Component
{
    use Searchable, Sortable , WithPagination;

    public EventForm $form;

    public function getMatches(): array
    {
        return [
            'name' => 'name',
            'date' => 'date',
        ];
    }

    public function openFormModal(?Event $event): void
    {
        $this->form->reset();

        if ($event->exists) {
            $this->form->setEvent($event);
        }

        Flux::modal('event-form')->show();
    }

    public function save(UpsertEventActions $actions): Response
    {
        $this->form->validate();

        $event = $this->form->event ?? new Event();

        $actions->handle($event, $this->form);
        $this->form->reset();
        Flux::toast('Event successfully saved!', variant: 'success');
        Flux::modal('event-form')->close();

        return response()->noContent();
    }

    public function open(Event $event, OpendEventActions $actions): Response
    {
        $actions->handle($event);

        Flux::toast('Event opened successfully!');

        return response()->noContent();
    }

    public function close(Event $event, ClosedEventActions $actions): Response
    {
        $actions->handle($event);

        Flux::toast('Event closed successfully!');

        return response()->noContent();
    }

    public function render(): \Illuminate\View\View
    {
        $query = Event::query();
        $query = $this->applySorting($query, 'date');
        $query = $this->applySearch($query, ['name']);
        $events = $query->paginate(10);

        return view('livewire.dashboard.admin.event-index', [
            'events' => $events,
        ]);
    }
}
