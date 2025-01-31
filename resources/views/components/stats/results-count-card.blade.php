<flux:card class="space-y-6">
    <div class="grid  grid-cols-4 gap-4 py-3">
       <div class="flex flex-col items-center justify-center">
              <flux:heading size="lg">MERON</flux:heading>
           <span
               class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
               :class="'bg-red-500'" x-text="resultCounts.meron"></span>
       </div>
        <div class="flex flex-col items-center justify-center">
            <flux:heading size="lg">WALA</flux:heading>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-blue-500'" x-text="resultCounts.wala" ></span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <flux:heading size="lg">DRAW</flux:heading>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-green-500'" x-text="resultCounts.draw"></span>
        </div>
        <div class="flex flex-col items-center justify-center">
            <flux:heading size="lg">CANCELLED</flux:heading>
            <span
                class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                :class="'bg-zinc-500'" x-text="resultCounts.cancelled"></span>
        </div>
    </div>
</flux:card>
