<div class="p-0! h-full flex flex-col gap-2">
    <div class="h-[10%]">
        <div class="flex flex-row justify-between items-center bg-dark text-center h-full">
            <p class="text-[40px] text-center font-semibold text-[#ffffff]">FIGHT # <span x-text="game.game_number"></span></p>
            <div x-bind:style="`background-color: ${game.status_color}`" class="px-4 py-2 rounded-lg animate-pulse">
                <p class="text-[40px] text-center font-semibold text-[#ffffff]">
                    BETTING: <span x-text="game.status === '{{ \App\Enums\GameStatus::PENDING->label() }}' ? 'STANDBY' : game.status" class="uppercase"></span>
                </p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-5 min-h-[85%] gap-2">
        <div class=" flex flex-col border-2 border-red-500 shadow-md overflow-hidden col-span-2">
            <div class="bg-red-500 text-white text-center px-4 py-2 font-semibold">
                <p class="text-[50px] font-extrabold antialiased">MERON</p>
            </div>
            <div class="p-4 flex flex-col items-center justify-center h-full">
                <div class="pt-6" x-show="game.result == 'Meron'">
                    <p class="text-[50px] text-[gold] font-bold text-center animate-pulse">WINNER!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <p class="text-[50px] text-[gray] font-bold text-center animate-pulse">CANCELLED!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Draw'">
                    <p class="text-[50px] text-[green] font-bold text-center animate-pulse">DRAW!</p>
                </div>
                <div class="pb-2">
                    <p class="text-[30px] text-green-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && game.meron_open">OPEN</p>
                    <p class="text-[30px] text-red-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && !game.meron_open">CLOSE</p>

                </div>


                <div class="pb-2">
                    <p class="text-[100px] text-[gold] font-bold text-center">  <livewire:countup value='game.meron_amount' /></p>
                    <p class="text-[50px] font-bold text-center text-green-500">PAYOUT: <span x-text="game.meron_odds"></span></p>
                </div>
            </div>
        </div>
        <div class=" flex flex-col border-2 border-green-500 shadow-md overflow-hidden">
            <div class="bg-green-500 text-white text-center px-4 py-1 font-semibold">
                <p class="text-[50px] font-extrabold antialiased">DRAW</p>
            </div>
            <div class="p-4 flex flex-col items-center justify-center h-full">
                <div class="pt-6" x-show="game.result == 'Meron' || game.result == 'Wala'">
                    <p class="text-[50px] text-[gold] font-bold text-center animate-pulse uppercase" x-text="game.result">!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <p class="text-[50px] text-[gray] font-bold text-center animate-pulse">CANCELLED!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Draw'">
                    <p class="text-[50px] text-[green] font-bold text-center animate-pulse uppercase">Winner!</p>
                </div>
                <div class="pb-2">
                    <p class="text-[30px] text-green-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && game.meron_open">OPEN</p>
                    <p class="text-[30px] text-red-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && !game.meron_open">CLOSE</p>

                </div>


                <div class="pt-5">
                    <p class="text-[50px] text-[gold] font-bold text-center"> <livewire:countup value='game.draw_amount' /></p>
                    <div class="pt-7">
                        <p class="text-[50px] font-bold text-center text-green-500">800.00%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class=" flex flex-col border-2 border-blue-500 shadow-md overflow-hidden col-span-2">
            <div class="bg-blue-500 text-white text-center px-4 py-2 font-semibold">
                <p class="text-[50px] font-extrabold antialiased">WALA</p>
            </div>
            <div class="p-4 flex flex-col items-center justify-center h-full">
                <div class="pt-6" x-show="game.result == 'Wala'">
                    <p class="text-[50px] text-[gold] font-bold text-center animate-pulse">WINNER!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <p class="text-[50px] text-[gray] font-bold text-center animate-pulse">CANCELLED!</p>
                </div>
                <div class="pt-6" x-show="game.result == 'Draw'">
                    <p class="text-[50px] text-[green] font-bold text-center animate-pulse">DRAW!</p>
                </div>
                <div class="pb-2">
                    <p class="text-[30px] text-green-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && game.wala_open">OPEN</p>
                    <p class="text-[30px] text-red-500 font-bold text-center text-ellipsis overflow-hidden antialiased animate-pulse" x-cloak x-show="game.status === 'Opened' && !game.wala_open">CLOSE</p>

                </div>


                <div class="pb-2">
                    <p class="text-[100px] text-[gold] font-bold text-center"> <livewire:countup value='game.wala_amount' /></p>
                    <p class="text-[50px] font-bold text-center text-green-500" >PAYOUT: <span x-text="game.wala_odds"></span></p>
                </div>
            </div>
        </div>
    </div>



</div>
