<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Events</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="flex p-4 items-center space-x-2">
        <div class=" w-2/6">
            <flux:input icon="magnifying-glass" placeholder="Search events" wire:model.live.debounce='search' />
        </div>

        <div class="">
            <flux:button variant="filled" icon-trailing="plus" wire:click="openFormModal()"> Create new event
            </flux:button>
        </div>

    </div>
    <flux:table :paginate="$events">
        <flux:columns>
            <flux:column>Event name</flux:column>
            <flux:column>Date</flux:column>
            <flux:column>No. of Games</flux:column>
            <flux:column>Status</flux:column>
            <flux:column></flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($events as $event)
            <flux:row :key="$event->id">
                <flux:cell>{{ $event->name }}</flux:cell>
                <flux:cell>{{ $event->dateFormatted() }}</flux:cell>
                <flux:cell>{{ $event->number_of_games }}</flux:cell>
                <flux:cell>
                    <flux:badge color="{{ $event->status->color() }}" size="sm" inset="top bottom">
                        {{ $event->status->label() }}
                    </flux:badge>
                </flux:cell>
                <flux:cell class="flex space-x-2 items-center justify-center">
                    @if($event->status->isEditable())
                    <flux:button size="sm" wire:click="openFormModal('{{ $event->uuid }}')">Edit</flux:button>
                    @endif
                    <flux:button size="sm" href="{{ route('events.show', $event->uuid) }}">View</flux:button>
                </flux:cell>


            </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>



    <flux:modal name="event-form" class="md:w-96 space-y-6">
        <form wire:submit='save'>
            <div>
                <flux:heading size="lg" class="mb-4">Event Form</flux:heading>
            </div>
            <flux:input label="Name" placeholder="Event name" wire:model="form.name" class="mb-3" />
            <flux:input label="Date" type="date" wire:model="form.date" class="mb-3" min="{{ now()->toDateString() }}" />
            <flux:input label="Start of game" type="number" wire:model="form.start_of_game" min="1" class="mb-3" />
            <flux:input label="Number of game" type="number" wire:model="form.number_of_games" min="50" class="mb-3" />
            <div class="flex pt-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save Event</flux:button>
            </div>
        </form>
    </flux:modal>
</div>