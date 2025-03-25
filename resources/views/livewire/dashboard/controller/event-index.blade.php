<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Game Controller</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <flux:table :paginate="$events">
        <flux:table.columns>
            <flux:table.column>Event name</flux:table.column>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>No. of Games</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($events as $event)
            <flux:table.rows wire:key="{{ $event->id }}">
                <flux:table.cell>{{ $event->name }}</flux:table.cell>
                <flux:table.cell>{{ $event->dateFormatted() }}</flux:table.cell>
                <flux:table.cell>{{ $event->number_of_games }}</flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $event->status->color() }}" size="sm" inset="top bottom">
                        {{ $event->status->label() }}
                    </flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button size="sm" href="{{ route('controller.events.game-controller.show', $event->uuid) }}">View
                    </flux:button>
                    
                </flux:table.cell>
            </flux:table.row>
            @endforeach


        </flux:table.rows>
    </flux:table>


</div>
