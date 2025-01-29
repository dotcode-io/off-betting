<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
            <flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>


        </div>
        <div>

        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="flex p-4 items-center space-x-2">
        <div class=" w-2/6">
            <flux:input icon="magnifying-glass" placeholder="Search events" />
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
            <flux:column>Status</flux:column>
            <flux:column></flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($events as $event)
                <flux:row :key="$event->id">
                    <flux:cell>{{ $event->name }}</flux:cell>
                    <flux:cell>{{ $event->dateFormated() }}</flux:cell>
                    <flux:cell>
                        <flux:badge color="{{ $event->status->color() }}" size="sm" inset="top bottom">
                            {{ $event->status->label() }}
                        </flux:badge>
                    </flux:cell>
                    <flux:cell>
                        <flux:button size="sm" wire:click="openFormModal('{{ $event->id }}')">Edit</flux:button>
                        <flux:button size="sm">View</flux:button>
                    </flux:cell>


                </flux:row>
            @endforeach



        </flux:rows>
    </flux:table>



    <flux:modal name="event-form" class="md:w-96 space-y-6">
        <form wire:submit='save'>
            <div>
                <flux:heading size="lg">Event Form</flux:heading>
            </div>
            <flux:input label="Name" placeholder="Event name" wire:model="form.name" />
            <flux:input label="Date" type="date" wire:model="form.date" />
            <flux:input label="Start of game" type="number" wire:model="form.start_of_game" />
            <flux:input label="Number of game" type="number" wire:model="form.number_of_games" />
            <div class="flex pt-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save event</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
