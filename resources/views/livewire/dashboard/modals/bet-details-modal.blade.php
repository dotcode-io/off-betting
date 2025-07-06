<flux:modal name="bet-details-modal" class="min-w-[28rem] space-y-6">
    @if(isset($bet))
        <div class="space-y-4">
            <flux:heading size="lg" class="font-bold!">Bet Details</flux:heading>
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

        <div class="flex justify-end gap-2 pt-2">
            <flux:modal.close>
                <flux:button>Close</flux:button>
            </flux:modal.close>
        </div>
    @endif
</flux:modal>
