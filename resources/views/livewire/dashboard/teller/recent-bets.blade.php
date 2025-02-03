<flux:card>
    <div>
        <div class="flex flex-row gap-1 mb-2">
            <flux:icon.document-text />
            <flux:heading size="lg">Recent Transactions</flux:heading>
        </div>
        <div class="py-2">
            <flux:separator variant="subtle" />
        </div>
        <flux:table :paginate="$bets">
            <flux:columns>
                <flux:column>Details</flux:column>
                <flux:column>Result</flux:column>
                <flux:column></flux:column>
            </flux:columns>

            <flux:rows>
                @foreach ($bets as $bet)
                <flux:row :key="$bet->id">
                    <flux:cell>
                        <div>
                            <div class="text-[yellow] text-[12px]">#{{ $bet->reference_no }}</div>

                            <div class="font-bold text-[yellowgreen]">GN: {{ $bet->eventGame->game_number }}</div>

                            <div class="mt-1 mb-2">
                                <flux:badge variant="solid" color="{{ $bet->side->color() }}" size="sm" inset="top bottom">
                                    {{ $bet->side->label() }}
                                </flux:badge>

                            </div>

                            <div>
                                Nickname: {{ $bet->nickname ?? 'N/A' }}
                            </div>
                            <div class="font-bold text-[skyblue]">
                                Bet: {{ number_format($bet->bet_amount, 2) }}
                            </div>

                            @if ($bet->isWin())
                            <div class="font-bold text-[green]">
                                Win Amount: {{ number_format($bet->win_amount, 2) }}
                            </div>
                            @endif

                        </div>
                    </flux:cell>
                    <flux:cell>
                        <flux:badge variant="solid" color="{{ $bet->result->color() }}" size="sm" inset="top bottom">
                            {{ $bet->result->label() }}
                        </flux:badge>

                    </flux:cell>
                    <flux:cell>
                        <flux:button size="sm" icon="printer" class="!bg-[white] !text-[black]" wire:click="reprintReceipt({{ $bet->id }})">REPRINT</flux:button>
                    </flux:cell>


                </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
    </div>
</flux:card>