<div class="p-4 bg-gray-700">
    <div class="grid  grid-cols-4">
        <div class="flex flex-col items-center justify-center">
            <div class="text-[14px] font-bold">MERON</div>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-red-600'" x-text="resultCounts.meron"></span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <div class="text-[14px] font-bold">WALA</div>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-blue-600'" x-text="resultCounts.wala"></span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <div class="text-[14px] font-bold">DRAW</div>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-green-600'" x-text="resultCounts.draw"></span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <div class="text-[14px] font-bold">CANCELLED</div>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-zinc-600'" x-text="resultCounts.cancelled"></span>
        </div>
    </div>
</div>