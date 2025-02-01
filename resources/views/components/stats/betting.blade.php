<flux:card class="space-y-6 !p-0">
    <div class="grid grid-cols-2">
        <div class="bg-red-500 flex flex-col items-center justify-center rounded-tl-[0.75rem]">
            <div class="my-5 text-center">
                <div class="pb-2">
                    <flux:heading size="xl">MERON</flux:heading>



                    <flux:heading size="lg" x-text="game.meron_name"></flux:heading>
                </div>


                <div class="pb-2">
                    <flux:heading size="xl" x-text="game.meron_bets"></flux:heading>
                    <flux:heading size="lg" x-text="game.meron_odds"></flux:heading>
                </div>

                <flux:heading>Bettors: <span x-text="game.meron_bettors"></span></flux:heading>

                <div class="pt-6" x-show="game.result == 'Meron'">
                    <flux:heading>WINNER</flux:heading>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <flux:heading>CANCELLED</flux:heading>
                </div>
            </div>

        </div>

        <div class="bg-blue-500 rounded-tr-[0.75rem]">
            <div class="my-5 text-center">
                <div class="pb-2">
                    <flux:heading size="xl">WALA</flux:heading>
                    <flux:heading size="lg" x-text="game.wala_name"></flux:heading>
                </div>


                <div class="pb-2">
                    <flux:heading size="xl" x-text="game.wala_bets"></flux:heading>
                    <flux:heading size="lg" x-text="game.wala_odds"></flux:heading>
                </div>

                <flux:heading>Bettors: <span x-text="game.wala_bettors"></span></flux:heading>
                <div class="pt-6" x-show="game.result == 'Wala'">
                    <flux:heading>WINNER</flux:heading>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <flux:heading>CANCELLED</flux:heading>
                </div>
            </div>
        </div>

        <div class="bg-green-500 col-span-2 rounded-b-[0.75rem]">
            <div class="my-5 text-center">
                <div class="pb-2">
                    <flux:heading size="xl">DRAW (800%): <span x-text="game.draw_bets"></span></flux:heading>
                </div>


                <div class="pb-2">
                    <flux:heading>Bettors: <span x-text="game.draw_bettors"></span></flux:heading>
                </div>
                <div class="pt-6" x-show="game.result == 'Draw'">
                    <flux:heading>WINNER</flux:heading>
                </div>
                <div class="pt-6" x-show="game.result == 'Cancelled'">
                    <flux:heading>CANCELLED</flux:heading>
                </div>

            </div>
        </div>

    </div>
</flux:card>