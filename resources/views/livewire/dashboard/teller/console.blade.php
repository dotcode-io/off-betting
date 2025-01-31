<div class="grid grid-cols-2 gap-4" x-data="consoleData">

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


    <flux:card class="space-y-6">

    </flux:card>
    <flux:card class="space-y-6">
        <form wire:submit="submitBet">
        <div class="flex">
            <div class="flex-1">
                <flux:heading size="lg">Fight #<span x-text="game.game_number"></span></flux:heading>
            </div>
        </div>
        <div class="flex space-x-2 min-h-[100px]">
            <button type="button" class="bg-red-500 w-full text-center pt-2 hover:cursor-pointer hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-red-300"  wire:click="setSide('meron')">
                <div class="pb-2">
                    <flux:heading size="xl">MERON</flux:heading>
                    <flux:heading size="lg">AVASRB</flux:heading>
                </div>
            </button>
            <button type="button" class="bg-green-500 w-full text-center pt-2 hover:cursor-pointer hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-green-300"  wire:click="setSide('draw')">
                <div class="pb-2">
                    <flux:heading size="xl">DRAW</flux:heading>
                    <flux:heading size="lg">800%</flux:heading>
                </div>
            </button>
            <button type="button" class="bg-blue-500 w-full text-center pt-2 hover:cursor-pointer hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-blue-300"  wire:click="setSide('wala')">
                <div class="pb-2">
                    <flux:heading size="xl">WALA</flux:heading>
                    <flux:heading size="lg">KAIZEN GF</flux:heading>
                </div>
            </button>
        </div>

        <div class="pt-5">
            <flux:input wire:model="betForm.amount" label="AMOUNT" x-mask:dynamic="$money($input)"  placeholder="ENTER AMOUNT TO BET"/>
            <div class="flex items-center justify-center gap-[4px] py-[16px]">

                <template x-for="(amount,index) in amountList" :key="`amount-${index}`">
                    <flux:button  @click="setAmount(amount.value)" variant="primary">
                        <span x-text="amount.label">

                        </span>
                    </flux:button>
                </template>



            </div>
        </div>


            <div class="grid grid-cols-3 gap-2 mb-4">
                <flux:button variant="primary" x-on:click="amountBet += '1'">1</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '2'">2</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '3'">3</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '4'">4</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '5'">5</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '6'">6</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '7'">7</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '8'">8</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '9'">9</flux:button>
                <flux:button variant="primary" x-on:click="amountBet += '0'">0</flux:button>
               <div class="col-span-2">
                   <flux:button variant="danger" x-on:click="amountBet = ''" class="w-full">Clear</flux:button>
               </div>
            </div>

            <!-- Clear Button -->


        <div>
            <flux:button variant="primary" class="w-full" type="submit"> SUBMIT BET AND PRINT</flux:button>
        </div>
        </form>



    </flux:card>
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
            game: @entangle('game'),
            amountBet: @entangle('betForm.amount'),
            amountList:[],
            init() {
                this.amountList = [
                    {
                        value:10,
                        label: "10"
                    },
                    {
                        value:20,
                        label: "20"
                    },
                    {
                        value:50,
                        label: "50"
                    },
                    {
                        value:100,
                        label: "100"
                    },
                    {
                        value:200,
                        label: "200"
                    },
                    {
                        value:300,
                        label: "300"
                    },
                    {
                        value:500,
                        label: "500"
                    },
                    {
                        value:1000,
                        label: "1,000"
                    },
                    {
                        value:2000,
                        label: "2,000"
                    },
                    {
                        value:5000,
                        label: "5,000"
                    },


                ]
            },
            setAmount(value) {
                this.amountBet = value
            },

        }));
    });
</script>
