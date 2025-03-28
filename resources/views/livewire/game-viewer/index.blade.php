<x-game-viewer.main :games="$games" :game="$game" :event="$event">
    <div class=" w-full ">
          <div>
              <x-game-viewer.betting />
          </div>
        <div class="pt-2">
            <x-game-viewer.reglahan />
        </div>

    </div>
</x-game-viewer.main>
