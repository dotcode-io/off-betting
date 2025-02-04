<x-game-viewer.main :games="$games" :game="$game" :event="$event">
    <div class="grid grid-flow-col grid-cols-5 grid-rows-4 gap-3 h-full">
        <div class="col-span-4 row-span-3 flex flex-col">
            <x-game-viewer.betting />
        </div>
        <div class="col-span-4 ">
            <x-game-viewer.reglahan />
        </div>
        <div class="row-span-4 ">
            <x-game-viewer.results-count />
            <x-game-viewer.results />
        </div>
    </div>
</x-game-viewer.main>