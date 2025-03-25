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
            <flux:table.column>Transaction Date</flux:table.column>
            <flux:table.column>Ref No.</flux:table.column>
            <flux:table.column>Bet Amount</flux:table.column>
            <flux:table.column>Bet Side</flux:table.column>
            <flux:table.column>Game</flux:table.column>
            <flux:table.column>Result</flux:table.column>
            <flux:table.column>Win Amount</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($bets as $bet)
            <flux:table.rows :key="$bet->id">
                <flux:table.cell>{{ $bet->created_at->format('M d, Y h:i:s A') }}</flux:table.cell>
                <flux:table.cell>{{ $bet->reference_no }}</flux:table.cell>
                <flux:table.cell><span class="font-bold text-[skyblue]">{{ number_format($bet->bet_amount, 2) }}</span></flux:table.cell>
                <flux:table.cell><span class="text-{{ $bet->side->color() }}-500 uppercase font-bold">{{ $bet->side->label() }}</span></flux:table.cell>
                <flux:table.cell>#{{ $bet->eventGame->game_number }}</flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $bet->result->color() }}" size="sm" inset="top bottom" variant="solid">
                        {{ $bet->result->label() }}
                    </flux:badge>

                </flux:table.cell>
                <flux:table.cell>
                    <span class="font-bold text-[yellowgreen]">{{ $bet->win_amount > 0 ? number_format($bet->win_amount, 2) : '-' }}</span>
                    @if($bet->is_claimed)
                    <flux:badge class="!h-[18px] !text-[10px]" color="blue" size="sm" variant="solid">
                        Claimed
                    </flux:badge>
                    @endif
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
