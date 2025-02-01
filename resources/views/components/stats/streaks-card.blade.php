<flux:card class="space-y-6">
    <div class="flex space-x-2 overflow-x-auto min-h-[200px] md:min-h-[250px]">
        <template x-for="(chunks,index) in streaks" :key="`game-streak-ul-${index}`">>
            <ul class="space-y-1">
                <template x-for="(item,index) in chunks" :key="`game-streak-li-${index}`">
                    <li>
                <span
                    class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center border rounded-full text-xm font-bold"
                    :class="'bg-' + item + '-500'" x-text="item.game_number"></span>
                    </li>
                </template>
            </ul>
        </template>
    </div>
</flux:card>
