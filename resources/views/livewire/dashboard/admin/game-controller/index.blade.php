<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
            <flux:subheading size="lg" class="mb-6">Here's what's new today</flux:subheading>


        </div>
        <div>

        </div>
    </div>
    <flux:separator variant="subtle"/>
    <flux:table :paginate="$events">
        <flux:columns>
            <flux:column>Event name</flux:column>
            <flux:column>Date</flux:column>
            <flux:column>Status</flux:column>
            <flux:column></flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($events as $event)
                <flux:row wire:key="{{ $event->id }}">
                    <flux:cell>{{ $event->name }}</flux:cell>
                    <flux:cell>{{ $event->dateFormated() }}</flux:cell>
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
