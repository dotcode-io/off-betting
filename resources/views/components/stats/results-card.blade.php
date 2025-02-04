<flux:card class="space-y-6">
    <div class="flex space-x-2 overflow-x-auto min-h-[200px] md:min-h-[250px]">
        <template x-for="(chunks,index) in results" :key="`game-result-ul-${index}`">>
            <ul class="space-y-1">
                <template x-for="(item,index) in chunks" :key="`game-result-li-${index}`">
                    <li>
                        <span
                            class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center border rounded-full text-xm font-bold"
                            :class="'bg-' + item.color + '-600'" x-text="item.game_number"></span>
                    </li>
                </template>
            </ul>
        </template>
    </div>
</flux:card>