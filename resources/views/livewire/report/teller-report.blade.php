<div class="pt-2">
    <div class="grid grid-cols-3 gap-2">
        <flux:card size="sm" class="pt-2 ">
            <flux:subheading>Total Bet Received</flux:subheading>

            <flux:heading size="xl" class="mb-1"> <flux:text color="green" size="xl">{{ number_format($this->data->sum('total_amount'),2) }}</flux:text></flux:heading>


        </flux:card>
        <flux:card size="sm" class="pt-2">
            <flux:subheading>Total Bet Winner</flux:subheading>

            <flux:heading size="xl" class="mb-1"><flux:text color="blue" size="xl">{{ number_format($this->data->sum('total_win_amount'),2) }}</flux:text></flux:heading>

        </flux:card>
        <flux:card size="sm" class="pt-2">
            <flux:subheading>Total Claim</flux:subheading>

            <flux:heading size="xl" class="mb-1"><flux:text color="red" size="xl"> {{ number_format($this->data->sum('total_claim_amount'),2) }}</flux:text></flux:heading>

        </flux:card>

    </div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>#</flux:table.column>
            <flux:table.column>Username</flux:table.column>
            <flux:table.column>Total Bet Received</flux:table.column>
            <flux:table.column>Total Bet Winner</flux:table.column>
            <flux:table.column>Total Claim</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($this->data as $key => $report)
                <flux:table.row :key="$report->user_id">
                    <flux:table.cell>{{ $key + 1}}</flux:table.cell>

                    <flux:table.cell>{{ $report->user->username }} </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="green" >{{ number_format($report->total_amount,2) }}</flux:text> </flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="blue"> {{ number_format($report->total_win_amount,2) }} </flux:text></flux:table.cell>
                    <flux:table.cell variant="strong"><flux:text color="red">{{ number_format($report->total_claim_amount,2) }} </flux:text></flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>
    </flux:table>

</div>
