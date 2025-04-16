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
                    <flux:button size="sm" wire:click="openBetDetailsModal('{{ $bet->id }}')">View Bet Details</flux:button>
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:modal name="void-claim" class="min-w-[22rem] space-y-6">
        <div class="space-y-4">
            <flux:heading size="lg">Void Transaction <span x-text="game.game_number"> </span></flux:heading>

            <flux:subheading>
                <p>Are you sure you want to void this transaction?</p>
            </flux:subheading>
        </div>

        <form wire:submit="voidClaim">
            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Yes, Void</flux:button>
            </div>
        </form>

    </flux:modal>

    <flux:modal name="bet-details" class="min-w-[28rem] space-y-6">
        <div class="space-y-4">
            <flux:heading size="lg" class="font-bold!">Bet Details <span x-text="game.game_number"> </span></flux:heading>
        </div>
        @if($bet)
            <div class="space-y-1">
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Reference No.
                    </div>
                    <div>
                        {{ $bet->reference_no }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Event
                    </div>
                    <div>
                        {{ $bet->event->name }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Game
                    </div>
                    <div>
                        #{{ $bet->eventGame->game_number }}
                    </div>
                </div>
            </div>
            <flux:separator variant="subtle" />
            <div class="space-y-1">
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Nickname
                    </div>
                    <div>
                        {{ $bet->nickname ?? '-' }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Bet Date
                    </div>
                    <div>
                        {{ $bet->created_at->format('Y-m-d h:i:s A') }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Bet Amount
                    </div>
                    <div class="font-bold">
                        {{ number_format($bet->bet_amount, 2) }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Bet Side
                    </div>
                    <div class="text-{{ $bet->side->color() }}-500 font-bold">
                        {{ $bet->side->label() }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Result
                    </div>
                    <div class="text-{{ $bet->result->color() }}-500 font-bold">
                        {{ $bet->result->label() }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Status
                    </div>
                    <div class="text-[{{ $bet->status->color() }}] font-bold">
                        {{ $bet->status->label() }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Winning Amount
                    </div>
                    <div class="font-bold text-[yellowgreen]">
                        {{ number_format($bet->win_amount, 2) }}
                    </div>
                </div>
            </div>
            <flux:separator variant="subtle" />
            <div class="space-y-1">
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Claimed At
                    </div>
                    <div>
                        {{ (new DateTime($bet->claimed_at))->format('Y-m-d h:i:s A') }}
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="text-gray-400">
                        Claimed By
                    </div>
                    <div>
                        {{ $bet->claimedBy->username }}
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end gap-2 pt-2">
            <flux:modal.close>
                <flux:button>Close</flux:button>
            </flux:modal.close>
        </div>

    </flux:modal>
</div>
