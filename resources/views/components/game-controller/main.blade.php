@props(['games'])
<div x-data="gameData">
    {{ $slot }}
</div>

<script>
    function chunkArray(array, chunkSize) {
        const result = [];
        const length = array.length;
        let index = 0;

        while (index < length) {
            result.push(array.slice(index, index + chunkSize));
            index += chunkSize;
        }

        return result;
    }

    function streak(array) {
        const result = [];
        let chunk = [];
        let currentType = null;

        for (const item of array) {
            // Check if the current item matches the streak conditions
            if (
                currentType === null || // Start of a new streak
                item.result === currentType || // Matches the current streak type
                item.result === 'draw' || // Special condition
                item.result === 'cancelled' // Special condition
            ) {
                // Update the current streak type if starting a new streak
                if (currentType === null) {
                    currentType = item.result;
                }

                // Add the item to the current chunk
                chunk.push(item.result);

                // If the chunk reaches 6 items, push it to the result and reset
                if (chunk.length === 6) {
                    result.push(chunk);
                    chunk = [];
                }
            } else {
                // If the streak is broken, push the current chunk (if not empty)
                if (chunk.length > 0) {
                    result.push(chunk);
                    chunk = [];
                }

                // Start a new streak with the current item
                currentType = item.result;
                chunk.push(item.result);
            }
        }

        // Push the last chunk if it's not empty
        if (chunk.length > 0) {
            result.push(chunk);
        }
    }


    function resultCount(array) {
        let meronCount = 0,
            walaCount = 0,
            drawCount = 0,
            canceledCount = 0;

        for (const item of array) {
            if (item.result === 'pending') continue; // Skip 'white' items directly

            if (item.result === 'meron') meronCount++;
            else if (item.result === 'wala') walaCount++;
            else if (item.result === 'draw') drawCount++;
            else if (item.result === 'cancelled') canceledCount++;
        }

        return {
            meron: meronCount,
            wala: walaCount,
            draw: drawCount,
            cancelled: canceledCount
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('gameData', () => ({
                // Entangle the Livewire `game` property with Alpine.js
                games: @entangle('games'),
                resultCounts: {
                    meron: 0,
                    wala: 0,
                    draw: 0,
                    cancelled: 0
                },
                results: [],
                streaks: [],

                init() {

                    this.$watch('games', value => {

                        this.resultCounts = {
                            meron: 0,
                            wala: 0,
                            draw: 0,
                            cancelled: 0
                        }
                        this.results = chunkArray(value, 6);
                    });
                },

                // Example method to update the game state
                updateGame() {
                    this.game = {
                        ...this.game,
                        status: 'updated'
                    };
                    console.log('Game updated:', this.game);
                }
            }));
        });
</script>
