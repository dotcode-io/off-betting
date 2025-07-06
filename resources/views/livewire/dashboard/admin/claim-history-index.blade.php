<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Claim History</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="flex py-4 items-center space-x-2">
        <div class=" w-1/6">
            <flux:input label="Search" icon="magnifying-glass" placeholder="Search bet" wire:model.live.debounce='search' />
        </div>
        <div class=" w-1/6">
            <flux:select label="Event" :filter="false" wire:model.live="eventId">
                @foreach ($events as $event)
                <flux:select.option value="{{ $event->id }}">
                    {{ $event->name }}
                </flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <div class=" w-1/6">
            <flux:input label="From" type="date" wire:model.live="from" />
        </div>
        <div class=" w-1/6">
            <flux:input label="To" type="date" wire:model.live="to" />
        </div>

    </div>
    <flux:table :paginate="$bets">
        <flux:table.columns>
            <flux:table.column>Ref No.</flux:table.column>
            <flux:table.column>Claimed At</flux:table.column>
            <flux:table.column>Claimed By</flux:table.column>
            <flux:table.column>Claimed Amount</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($bets as $bet)
            <flux:table.rows :key="$bet->id">
                <flux:table.cell>{{ $bet->reference_no }}</flux:table.cell>
                <flux:table.cell>{{ (new DateTime($bet->claimed_at))->format('M d, Y h:i:s A') }}</flux:table.cell>
                <flux:table.cell>{{ $bet->claimedBy->username }}</flux:table.cell>
                <flux:table.cell>
                    <span class="font-bold text-[yellowgreen]">{{ $bet->win_amount > 0 ? number_format($bet->win_amount, 2) : '-' }}</span>

                </flux:table.cell>
                <flux:table.cell class="flex space-x-2 items-center justify-center">
                    <flux:button size="sm" variant="danger" wire:click="$dispatch('void-claim', { betId: {{ $bet->id }} })">Void</flux:button>
                    <flux:button size="sm" wire:click="$dispatch('bet-details', { betId: {{ $bet->id }} })"
                    >View Bet Details</flux:button>
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <!-- Standalone Modal Components -->
    <livewire:dashboard.modals.void-claim-modal />
    <livewire:dashboard.modals.bet-details-modal />
</div>
