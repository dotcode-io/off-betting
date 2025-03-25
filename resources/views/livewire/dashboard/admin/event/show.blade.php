<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Event Details</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="mt-4 mb-4">
        <flux:card>
            <div class="grid grid-cols-3">
                <div class="flex flex-col gap-5">
                    <div>
                        <flux:heading level="3">{{ $event->name }}</flux:heading>
                        <flux:subheading>Event Name</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ $event->dateFormatted() }}</flux:heading>
                        <flux:subheading>Event Date</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ $event->openedAtFormatted() }}</flux:heading>
                        <flux:subheading>Opened At</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ $event->closedAtFormatted() }}</flux:heading>
                        <flux:subheading>Closed At</flux:subheading>
                    </div>
                </div>
                <div class="flex flex-col gap-5">
                    <div>
                        <flux:heading level="3">{{ $event->number_of_games }}</flux:heading>
                        <flux:subheading>Number of Games</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ number_format($event->total_bets, 2) }}</flux:heading>
                        <flux:subheading>Total Bets</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ number_format($event->total_draw_earnings, 2) }}</flux:heading>
                        <flux:subheading>Draw Earnings</flux:subheading>
                    </div>
                    <div>
                        <flux:heading level="3">{{ number_format($event->total_earnings, 2) }}</flux:heading>
                        <flux:subheading>Total Earnings</flux:subheading>
                    </div>

                </div>
                <div class="flex flex-col gap-5">
                    <flux:card class="space-y-6">
                        <div class="grid  grid-cols-4 gap-4 py-3">
                            <div class="flex flex-col items-center justify-center">
                                <flux:heading size="lg">MERON</flux:heading>
                                <span
                                    class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                                    :class="'bg-red-600'">{{ $meron }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <flux:heading size="lg">WALA</flux:heading>
                                <span
                                    class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                                    :class="'bg-blue-600'">{{ $wala }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <flux:heading size="lg">DRAW</flux:heading>
                                <span
                                    class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                                    :class="'bg-green-600'">{{ $draw }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <flux:heading size="lg">CANCELLED</flux:heading>
                                <span
                                    class="w-12 h-12 flex items-center justify-center border rounded-full text-xm font-bold"
                                    :class="'bg-zinc-600'">{{ $cancelled }}</span>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </div>


        </flux:card>
    </div>
    <flux:table :paginate="$games">
        <flux:table.columns>
            <flux:table.column>Game#</flux:table.column>
            <flux:table.column>Meron</flux:table.column>
            <flux:table.column>Wala</flux:table.column>
            <flux:table.column>Draw</flux:table.column>
            <flux:table.column>Earnings</flux:table.column>
            <flux:table.column>Draw Earnings</flux:table.column>
            <flux:table.column>Result</flux:table.column>
            <flux:table.column>Status</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($games as $game)
            <flux:table.rows :key="$game->id">
                <flux:table.cell>{{ $game->game_number }}</flux:table.cell>
                <flux:table.cell>
                    <div>Entry: {{ $game->meron_entry }}</div>
                    <div>Bets: {{ number_format($game->meron_bets, 2) }}</div>
                    <div>Bettors: {{ number_format($game->meron_bettors) }}</div>
                    <div>Odds: {{ number_format($game->meron_odds, 2) }}%</div>
                </flux:table.cell>
                <flux:table.cell>
                    <div>Entry: {{ $game->wala_entry }}</div>
                    <div>Bets: {{ number_format($game->wala_bets, 2) }}</div>
                    <div>Bettors: {{ number_format($game->wala_bettors) }}</div>
                    <div>Odds: {{ number_format($game->wala_odds, 2) }}%</div>
                </flux:table.cell>
                <flux:table.cell>
                    <div>Bets: {{ number_format($game->draw_bets, 2) }}</div>
                    <div>Bettors: {{ number_format($game->draw_bettors) }}</div>
                </flux:table.cell>
                <flux:table.cell>{{ number_format($game->earnings, 2) }}</flux:table.cell>
                <flux:table.cell>{{ number_format($game->draw_earnings, 2) }}</flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $game->result->color() }}" size="sm" variant="solid">
                        {{ $game->result->label() }}
                    </flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $game->status->color() }}" size="sm" variant="solid">
                        {{ $game->status->label() }}
                    </flux:badge>
                </flux:table.cell>

            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
