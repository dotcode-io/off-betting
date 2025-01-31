@props(['games','game'])
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
        const data = array.filter((item) => item.result !== 'pending');
        const result = [];
        let currentStreak = [];

        for (let i = 0; i < data.length; i++) {
            const currentResult = data[i].result;

            // If the current streak is empty or matches the last element, add to current streak
            if (
                currentStreak.length === 0 ||
                currentResult === currentStreak[0] ||
                currentResult === 'draw' ||
                currentResult === 'cancelled'
            ) {
                currentStreak.push(data[i].color);
            } else {
                // If the current streak exceeds 6, split it into chunks of 6
                if (currentStreak.length > 6) {
                    for (let j = 0; j < currentStreak.length; j += 6) {
                        result.push(currentStreak.slice(j, j + 6));
                    }
                } else {
                    result.push(currentStreak);
                }
                currentStreak = [currentResult]; // Start a new streak
            }
        }

// Handle the last streak
        if (currentStreak.length > 6) {
            for (let j = 0; j < currentStreak.length; j += 6) {
                result.push(currentStreak.slice(j, j + 6));
            }
        } else if (currentStreak.length > 0) {
            result.push(currentStreak);
        }


        return result;
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
    }

        document.addEventListener('alpine:init', () => {
            Alpine.data('gameData', () => ({
                // Entangle the Livewire `game` property with Alpine.js
                games: @entangle('games'),
                game: @entangle('game'),
                resultCounts: {
                    meron: 0,
                    wala: 0,
                    draw: 0,
                    cancelled: 0
                },
                results: [],
                streaks: [],

                init() {
                    this.results = chunkArray(this.games, 6);
                    this.resultCounts = resultCount(this.games);
                    this.streaks = streak(this.games);



                    this.$watch('games', value => {



                    });
                }

            }));
        });
</script>
