<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Game Controller</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
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
            <flux:row wire:key="{{ $event->id }}">
                <flux:cell>{{ $event->name }}</flux:cell>
                <flux:cell>{{ $event->dateFormatted() }}</flux:cell>
                <flux:cell>{{ $event->number_of_games }}</flux:cell>
                <flux:cell>
                    <flux:badge color="{{ $event->status->color() }}" size="sm" inset="top bottom">
                        {{ $event->status->label() }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>
                    <flux:button size="sm" href="{{ route('events.game-controller.show', $event->uuid) }}">View
                    </flux:button>
                </flux:cell>
            </flux:row>
            @endforeach


        </flux:rows>
    </flux:table>


</div>