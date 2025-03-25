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
            <flux:table.columns>
                <flux:table.column>Details</flux:table.column>
                <flux:table.column>Result</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($bets as $bet)
                <flux:table.rows :key="$bet->id">
                    <flux:table.cell>
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
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge variant="solid" color="{{ $bet->result->color() }}" size="sm">
                            {{ $bet->result->label() }}
                        </flux:badge>

                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>

                            <flux:navmenu>
                                <flux:navmenu.item icon="printer" wire:click="reprintReceipt({{ $bet->id }})">Reprint</flux:navmenu.item>
                            </flux:navmenu>
                        </flux:dropdown>
                    </flux:table.cell>


                </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</flux:card>
