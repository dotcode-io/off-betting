<div class="pt-2">
    <div class="grid grid-cols-3 gap-2">
        <flux:card size="sm" class="pt-2 ">
            <flux:subheading>Total Bet Received</flux:subheading>

            <flux:heading size="xl" class="mb-1"> <flux:text color="green" size="xl">100</flux:text></flux:heading>


        </flux:card>


    </div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Fight#</flux:table.column>
            <flux:table.column>Username</flux:table.column>
            <flux:table.column>Meron</flux:table.column>
            <flux:table.column>Wala</flux:table.column>
            <flux:table.column>Total Bet</flux:table.column>
            <flux:table.column>Plasada</flux:table.column>
            <flux:table.column>Win Amount</flux:table.column>
            <flux:table.column>Win/Lose</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($this->data as $key => $report)
                <flux:table.row wire:key="{{ $report->user_id }}_{{ $report->event_game_id }}">
                    <flux:table.cell>{{$report->eventGame->game_number}}</flux:table.cell>
                    <flux:table.cell>{{ $report->user->username }} </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="red" >{{ number_format($report->meron_amount,2) }}</flux:text> </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="blue"> {{ number_format($report->wala_amount,2) }} </flux:text></flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="green" >{{ number_format($report->meron_amount + $report->wala_amount,2) }}</flux:text> </flux:table.cell>

                    @php
                        $commission= ($report->meron_amount + $report->wala_amount) * ($report->eventGame->plasada / 100);
                        $winLose = ($report->total_win_amount + $commission) - ($report->meron_amount + $report->wala_amount);
                    @endphp
                    <flux:table.cell variant="strong">
                        <div class="flex flex-col justify-center">
                            <flux:text color="red" >({{ number_format($report->eventGame->plasada,2) }})</flux:text>
                            <flux:text color="green" >{{ number_format($commission,2) }}</flux:text>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="green" >{{ number_format($report->total_win_amount,2) }}</flux:text> </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="{{ $winLose < 0 ? 'red':'green' }}" >{{ number_format($winLose,2) }}</flux:text> </flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>

</div>
