<div x-data="consoleData">
    @if($event)
    <div id="receipt-content" style="display: none;">
        <div class="receipt">

            <hr>
            <div class="details">
                <p>Event: <span id="event"></span></p>
                <p>Fight#: <span id="fight"></span> </p>
                <p>Side: <span id="side"></span> </p>
                <p>Amount: <span id="amount"></span> </p>
                <p>Date: <span id="date"></span> </p>
                <p>Time: <span id="time"></span> </p>
                <p>Teller: <span id="teller"></span> </p>
                <p>Receipt #: <span id="ref"></span> </p>
            </div>

            <hr>
            <p>Thank you for your bet!</p>
        </div>
    </div>
    <flux:card class="w-full !bg-[#232323]">
        <form wire:submit="submitBet">
            <div class="flex">
                <flux:icon.play-circle variant="solid" class="text-[yellow] animate-pulse" />
                <flux:heading size="lg" class="!text-[yellow]">{{ strtoupper($event->name) }} <em class="text-[gray]">({{ strtoupper($event->dateFormatted()) }})</em></flux:heading>
            </div>
            <div class="py-2">
                <flux:separator variant="subtle" />
            </div>
            <div class="flex">
                <div class="flex justify-between items-center w-full">
                    <flux:heading size="lg">Fight #<span x-text="game.game_number"></span></flux:heading>
                    <flux:badge color="{{ $game['status_color'] }}" variant="pill" class="animate-pulse ">
                        {{ strtoupper($game['status']) }}
                    </flux:badge>
                </div>
            </div>
            <div class="flex space-x-2 min-h-[100px] pt-5">
                <button type="button" class="bg-red-500 w-full rounded-lg text-center pt-2 hover:cursor-pointer hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-red-300 @if($side === 'meron')border-4 border-yellow-600 bg-red-600 animate-pulse @endif" wire:click="setSide('meron')" :disabled="game.status !== 'Opened'">
                    <div class="pb-2">
                        <flux:heading size="xl">MERON</flux:heading>
                        <flux:heading size="lg" x-text="game.meron_name"></flux:heading>
                    </div>
                </button>
                <button type="button" class="bg-green-500 w-full rounded-lg text-center pt-2 hover:cursor-pointer hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-green-300 @if($side === 'draw')border-4 border-yellow-600 bg-green-600 animate-pulse @endif" wire:click="setSide('draw')" :disabled="game.status !== 'Opened'">
                    <div class="pb-2">
                        <flux:heading size="xl">DRAW</flux:heading>
                        <flux:heading size="lg">8X</flux:heading>
                    </div>
                </button>
                <button type="button" class="bg-blue-500 w-full rounded-lg text-center pt-2 hover:cursor-pointer hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-blue-300 @if($side === 'wala')border-4 border-yellow-600 bg-blue-600 animate-pulse @endif" wire:click="setSide('wala')" :disabled="game.status !== 'Opened'">
                    <div class="pb-2">
                        <flux:heading size="xl">WALA</flux:heading>
                        <flux:heading size="lg" x-text="game.wala_name">KAIZEN GF</flux:heading>
                    </div>
                </button>
            </div>

            <div class="pt-5">
                <flux:input wire:model="betForm.amount" label="AMOUNT" x-mask:dynamic="$money($input)" placeholder="ENTER AMOUNT TO BET" />
                <div class="flex overflow-x-auto   gap-[4px] py-[16px]">

                    <template x-for="(amount,index) in amountList" :key="`amount-${index}`">
                        <flux:button @click="setAmount(amount.value)">
                            <span x-text="amount.label">

                            </span>
                        </flux:button>
                    </template>



                </div>
            </div>


            <div class="grid grid-cols-3 gap-2 mb-4">
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '1'">1</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '2'">2</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '3'">3</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '4'">4</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '5'">5</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '6'">6</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '7'">7</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '8'">8</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '9'">9</flux:button>
                <flux:button class="!bg-[#fff44f]" variant="primary" x-on:click="amountBet += '0'">0</flux:button>
                <div class="col-span-2">
                    <flux:button variant="danger" x-on:click="amountBet = ''" class="w-full">Clear</flux:button>
                </div>
            </div>

            <!-- Clear Button -->


            <div>
                <flux:button class="w-full !bg-[#1338be]" variant="ghost" type="submit"> SUBMIT BET AND PRINT</flux:button>
            </div>
        </form>



    </flux:card>
    @else
    <div>
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" level="1">No Event Today</flux:heading>
            </div>
        </div>
    </div>

    @endif
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


    document.addEventListener('alpine:init', () => {
        Alpine.data('consoleData', () => ({
            game: @entangle('game').live,
            amountBet: @entangle('betForm.amount'),
            amountList: [],
            init() {
                this.amountList = [{
                        value: 20,
                        label: "20"
                    },
                    {
                        value: 50,
                        label: "50"
                    }, {
                        value: 100,
                        label: "100"
                    },
                    {
                        value: 200,
                        label: "200"
                    },
                    {
                        value: 300,
                        label: "300"
                    },
                    {
                        value: 500,
                        label: "500"
                    },
                    {
                        value: 1000,
                        label: "1,000"
                    },
                    {
                        value: 2000,
                        label: "2,000"
                    },
                    {
                        value: 5000,
                        label: "5,000"
                    },


                ]

                Echo.channel(`game-event.{{ $event->uuid }}`)
                    .listen('GameEvent', (e) => {
                        this.game = e.current
                            if (e.next) {
                                setTimeout(() => {
                                    this.game = e.next;
                                }, 4000);
                            }
                    });
            },
            setAmount(value) {
                this.amountBet = value
            },

        }));
    });
</script>
