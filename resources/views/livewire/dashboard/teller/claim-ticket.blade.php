<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Claim Ticket</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="mt-4">
        @if(!$bet)
        <flux:card class="w-1/3">
            <form wire:submit='checkTicket'>
                <div>
                    <flux:heading size="lg" class="mb-4">Claim Ticket</flux:heading>
                </div>
                <flux:input label="Ticket Reference No." placeholder="Enter ticket reference number" autofocus wire:model="reference_no" class="mb-3" clearable />
                <div class="flex pt-2">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Check Ticket</flux:button>
                </div>
            </form>
        </flux:card>
        @endif

        @if($bet)
        <flux:card class="w-1/3">
            <div class="space-y-4">
                <flux:heading size="lg" class="!font-bold">Bet Details <span x-text="game.game_number"> </span></flux:heading>
            </div>
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
                    <div class="text-gray-400 text-[20px]">
                        Winning Amount
                    </div>
                    <div class="font-bold text-[yellowgreen] text-[30px]">
                        {{ number_format($bet->win_amount, 2) }}
                    </div>
                </div>
            </div>
            <flux:separator variant="subtle" />


            <div class="flex justify-end gap-2 pt-2">
                <flux:button wire:click="close()">Close</flux:button>
                <flux:button icon="check" variant="primary" wire:click="claim()">Claim Winning</flux:button>
            </div>
        </flux:card>
        @endif
    </div>
</div>