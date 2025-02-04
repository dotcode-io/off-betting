<div class="flex flex-col overflow-scroll h-[1100px]">
    <template x-for="(item,index) in gameResults" :key="`result-ul-${index}`">
        <div class="flex space-x-2 justify-between border border-[0.5px] border-gray-400 items-center px-5 min-h-[60px] mt-2">
            <p class="text-[18px] font-semibold text-gray-400">FIGHT# <span x-text="item.game_number" class="text-[white]"></span></p>
            <p x-bind:style="`color: ${item.color}`" class="text-[22px] font-bold uppercase" x-text="item.result"></p>
        </div>
    </template>
</div>