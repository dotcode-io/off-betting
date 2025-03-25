
<div class="pt-2">
    <div class="grid grid-cols-2 gap-2">
        <flux:card size="sm" class="pt-2 ">
            <flux:subheading>Total Earning</flux:subheading>

            <flux:heading size="xl" class="mb-1">{{ $this->totals->total_earnings }}</flux:heading>


        </flux:card>
        <flux:card size="sm" class="pt-2">
            <flux:subheading>Total Draw Earning</flux:subheading>

            <flux:heading size="xl" class="mb-1">{{ $this->totals->total_draw_earnings }}</flux:heading>

        </flux:card>

    </div>
<flux:table :paginate="$this->games">
    <flux:table.columns>
        <flux:table.column>Fight #</flux:table.column>
        <flux:table.column>Meron Total Bets</flux:table.column>
        <flux:table.column>Meron Total Bettors</flux:table.column>
        <flux:table.column>Wala Total Bets</flux:table.column>
        <flux:table.column>Wala Total Bettors</flux:table.column>
        <flux:table.column>Draw Total Bets</flux:table.column>
        <flux:table.column>Draw Total Bettors</flux:table.column>
        <flux:table.column>Earnings</flux:table.column>
        <flux:table.column>Draw Earnings</flux:table.column>

    </flux:table.columns>

    <flux:table.rows>
        @foreach($this->games as $game)
            <flux:table.row :key="$game->id">
                <flux:table.cell>{{ $game->game_number }}</flux:table.cell>
                <flux:table.cell> <flux:text color="red"> {{ number_format($game->meron_bets,2) }}</flux:text></flux:table.cell>
                <flux:table.cell><flux:text color="red"> {{ $game->meron_bettors }}</flux:text></flux:table.cell>
                <flux:table.cell><flux:text color="blue">  {{ number_format($game->wala_bets,2) }} </flux:text> </flux:table.cell>
                <flux:table.cell> <flux:text color="blue">  {{ $game->wala_bettors }} </flux:text></flux:table.cell>
                <flux:table.cell><flux:text color="green"> {{ number_format($game->draw_bets,2) }} </flux:text></flux:table.cell>
                <flux:table.cell><flux:text color="green"> {{ $game->draw_bettors }} </flux:text> </flux:table.cell>
                <flux:table.cell > <flux:text color="green"> {{ number_format($game->earnings,2) }}</flux:text> </flux:table.cell>
                <flux:table.cell> <flux:text color="{{ $game->draw_earnings < 0 ? 'red':'green' }}"> {{ number_format($game->draw_earnings,2) }}</flux:text> </flux:table.cell>
            </flux:table.row>
        @endforeach


    </flux:table.rows>
</flux:table>
</div>

