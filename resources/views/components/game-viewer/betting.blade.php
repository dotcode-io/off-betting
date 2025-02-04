<div class="!p-0 h-full flex flex-col gap-2">
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
    <div class="grid grid-cols-2 min-h-[85%] gap-2">
        <div class=" flex flex-col border-2 border-red-500 shadow-md overflow-hidden">
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
                    <p class="text-[30px] text-gray-200 font-bold text-center text-ellipsis overflow-hidden antialiased" x-text="game.meron_name"></p>

                </div>


                <div class="pb-2">
                    <p class="text-[100px] text-[gold] font-bold text-center" x-text="game.meron_bets"></p>
                    <p class="text-[50px] font-bold text-center">PAYOUT: <span x-text="game.meron_odds"></span></p>
                </div>
            </div>
        </div>
        <div class=" flex flex-col border-2 border-blue-500 shadow-md overflow-hidden">
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
                    <p class="text-[30px] text-gray-200 font-bold text-center text-ellipsis overflow-hidden antialiased" x-text="game.wala_name"></p>

                </div>


                <div class="pb-2">
                    <p class="text-[100px] text-[gold] font-bold text-center" x-text="game.wala_bets"></p>
                    <p class="text-[50px] font-bold text-center">PAYOUT: <span x-text="game.wala_odds"></span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="h-[5%] flex justify-center items-center bg-dark text-center ">
        <p class="text-[24px] animate-pulse text-[yellowgreen] font-semibold">PAYOUTS BELOW 140% WILL BE CANCELLED !!!</p>
    </div>
</div>