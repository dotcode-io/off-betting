<div class="flex flex-col overflow-scroll h-[1100px]">
    <template x-for="(item,index) in gameResults" :key="`result-ul-${index}`">
        <div class="flex space-x-2 justify-between border border-[0.5px] border-gray-400 items-center px-5 min-h-[60px] mt-2">
            <p class="text-[18px] font-semibold text-gray-400">FIGHT# <span x-text="item.game_number" class="text-[white] "  :class="'bg-' + item.color+ '-500'" ></span></p>
            <p  class="text-[22px] font-bold uppercase " x-text="item.result" :class="'bg-' + item.color+ '-500'" ></p>
        </div>
    </template>
</div>
