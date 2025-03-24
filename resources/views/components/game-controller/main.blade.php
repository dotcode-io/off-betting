@props(['games','game', 'event','rankings'])
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
            const currentResult = data[i].color;

            // If the current streak is empty or matches the last element, add to current streak
            if (
                currentStreak.length === 0 ||
                currentResult === currentStreak[0] ||
                currentResult === 'green' ||
                currentResult === 'zinc'
            ) {
                currentStreak.push(currentResult);
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
            rankings: @entangle('rankings'),
            init() {
                this.results = chunkArray(this.games, 6);
                this.resultCounts = resultCount(this.games);
                this.streaks = streak(this.games);



                this.$watch('games', value => {
                    this.results = chunkArray(value, 6);
                    this.resultCounts = resultCount(value);
                    this.streaks = streak(value);

                });

                Echo.channel(`game-event.{{ $event->uuid }}`)
                    .listen('.game-event', (e) => {
                        this.game = e.current

                        if (e.next) {

                            const index = this.games.findIndex((item) => item.game_number === e.current.game_number);
                            if (index !== -1) {
                                this.games[index] = {
                                    id: e.current.id,
                                    game_number: e.current.game_number,
                                    color: e.current.result_color,
                                    result: e.current.result_value,
                                };
                            }
                            console.log(this.games[index], e.current)
                            setTimeout(() => {
                                this.game = e.next;
                            }, 4000);
                        }

                    })
                    .listen('BetRankingsEvent', (e) => {
                        console.log(e)
                        this.rankings = e.rankings;
                    });
            }

        }));
    });
</script>