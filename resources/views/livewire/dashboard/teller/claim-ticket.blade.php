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
                <flux:heading size="lg" class="font-bold!">Bet Details <span x-text="game.game_number"> </span></flux:heading>
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
                @if(!$bet->is_claimed)
                <flux:button icon="check" variant="primary" wire:click="claim()">Claim Winning</flux:button>
                @else
                <flux:button icon="printer" variant="primary" wire:click="reprint()">Reprint</flux:button>
                @endif
            </div>
        </flux:card>
        @endif
    </div>
    <flux:modal name="print-bet" class="min-w-[28rem] space-y-6" :dismissible="false">
        <div id="betReceipt" style="display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: white; height: 400px; padding-top:30px;">
            @if($bet)
            <div style="text-align: center; color: #000000; font-size: 12px; font-weight: bold;">{{ $bet->event->name }}</div>
            <div style="text-align: center; color: #000000; font-size: 11px;">Claim: {{ $bet->claimed_at?->format('F d, Y h:i:s A') }}</div>
            <div style="text-align: center; color: #000000; font-size: 11px;">Teller: {{ $bet->claimedBy?->username }}</div>

            <div style="text-align: center; color: #000000; font-size: 12px; font-weight: bold; margin-top: 20px;">Ref: {{ $bet->reference_no }}</div>
            <div style="text-align: center; color: #000000; font-size: 11px;">Nickname: {{ $bet->nickname ?? '-' }}</div>
            <div style="text-align: center; color: #000000; font-size: 14px;">Side: {{ $bet->side->label() }}</div>
            <div style="text-align: center; color: #000000; font-size: 14px; font-weight: bold;">Bet: ₱ {{ number_format($bet->bet_amount, 2) }}</div>
            <div style="text-align: center; color: #000000; font-size: 14px; font-weight: bold;">Win Amount: ₱ {{ number_format($bet->win_amount, 2) }}</div>
            @endif
        </div>
        <flux:button icon="printer" variant="primary" type="button" wire:click="printBet()"> Print
        </flux:button>
    </flux:modal>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>

<script>
    window.addEventListener('silent-print', (event) => {
        const data = event.detail[0].printData
        document.getElementById('event').innerText = data.event;
        document.getElementById('fight').innerText = data.fight;
        document.getElementById('side').innerText = data.side;
        document.getElementById('amount').innerText = data.amount;
        document.getElementById('date').innerText = data.date;
        document.getElementById('time').innerText = data.time;
        document.getElementById('teller').innerText = data.teller;
        document.getElementById('ref').innerText = data.ref;


        let printFrame = document.createElement('iframe');
        printFrame.style.position = 'absolute';
        printFrame.style.width = '0px';
        printFrame.style.height = '0px';
        printFrame.style.border = 'none';
        document.body.appendChild(printFrame);

        let doc = printFrame.contentDocument || printFrame.contentWindow.document;
        doc.open();
        doc.write(document.getElementById('receipt-content').innerHTML);
        doc.close();

        printFrame.contentWindow.focus();
        printFrame.contentWindow.print();

        setTimeout(() => {
            document.body.removeChild(printFrame);
        }, 1000);
    });

    window.addEventListener('bet-to-print', (event) => {
        let printContents = document.getElementById('betReceipt').innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    });
</script>