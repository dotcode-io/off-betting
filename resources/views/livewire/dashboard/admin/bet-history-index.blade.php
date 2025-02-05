<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Bet History</flux:heading>
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
                <flux:option value="{{ $event->id }}">
                    {{ $event->name }}
                </flux:option>
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
        <flux:columns>
            <flux:column>Transaction Date</flux:column>
            <flux:column>Ref No.</flux:column>
            <flux:column>Bet Amount</flux:column>
            <flux:column>Bet Side</flux:column>
            <flux:column>Game</flux:column>
            <flux:column>Result</flux:column>
            <flux:column>Win Amount</flux:column>
        </flux:columns>

        <flux:rows>
            @foreach ($bets as $bet)
            <flux:row :key="$bet->id">
                <flux:cell>{{ $bet->created_at->format('M d, Y h:i:s A') }}</flux:cell>
                <flux:cell>{{ $bet->reference_no }}</flux:cell>
                <flux:cell><span class="font-bold text-[skyblue]">{{ number_format($bet->bet_amount, 2) }}</span></flux:cell>
                <flux:cell><span class="text-{{ $bet->side->color() }}-500 uppercase font-bold">{{ $bet->side->label() }}</span></flux:cell>
                <flux:cell>#{{ $bet->eventGame->game_number }}</flux:cell>
                <flux:cell>
                    <flux:badge color="{{ $bet->result->color() }}" size="sm" inset="top bottom" variant="solid">
                        {{ $bet->result->label() }}
                    </flux:badge>

                </flux:cell>
                <flux:cell>
                    <span class="font-bold text-[yellowgreen]">{{ $bet->win_amount > 0 ? number_format($bet->win_amount, 2) : '-' }}</span>
                    @if($bet->is_claimed)
                    <flux:badge class="!h-[18px] !text-[10px]" color="blue" size="sm" variant="solid">
                        Claimed
                    </flux:badge>
                    @endif
                </flux:cell>
            </flux:row>
            @endforeach
        </flux:rows>
    </flux:table>
</div>