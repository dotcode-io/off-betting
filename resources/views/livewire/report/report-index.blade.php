<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Reports {{ $event->name }}</flux:heading>
        </div>
    </div>

    <div>
        <flux:tabs class="px-4">
            <flux:tab name="event" wire:click="$set('selectedTab', 'event')">Event</flux:tab>
            <flux:tab name="teller" wire:click="$set('selectedTab', 'teller')">Teller</flux:tab>
            <flux:tab name="account">GB</flux:tab>
        </flux:tabs>
        <livewire:dynamic-component :is="$view" :key="$view" :$event />
    </div>

</div>
