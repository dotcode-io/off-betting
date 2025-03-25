<flux:modal name="view-wallet-modal" class="md:w-96">
   @if($user)
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Wallet Transaction</flux:heading>
                <flux:text class="mt-2">Current Balance: {{ number_format($user?->wallet_amount,2) }}</flux:text>
            </div>
            <flux:table :paginate="$this->walletLogs">
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Previews</flux:table.column>
                    <flux:table.column>Current</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($this->walletLogs as $log)
                        <flux:table.row :key="$log->id">
                            <flux:table.cell>>{{ $log->dateFormatted() }}</flux:table.cell>>
                            <flux:table.cell>>{{ $log->description }}</flux:table.cell>>
                            <flux:table.cell>><flux:badge color="{{ $log->type->color() }}" size="sm" inset="top bottom">{{ $log->type->label() }}</flux:badge></flux:table.cell>>
                            <flux:table.cell> variant="strong">{{ number_format($log->amount,2) }}</flux:table.cell>>
                            <flux:table.cell> variant="strong">{{ number_format($log->previous_balance,2) }}</flux:table.cell>>
                            <flux:table.cell> variant="strong">{{ number_format($log->current_balance,2) }}</flux:table.cell>>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
   @endif
</flux:modal>
