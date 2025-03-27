<flux:modal name="view-wallet-modal" class="!w-full">
   @if($user)
        <div class="space-y-6">
            <div class="flex justify-between">

            <div>
                <flux:heading size="lg">Wallet Transaction</flux:heading>
                <flux:text class="mt-2">Current Balance: {{ number_format($user?->wallet_amount,2) }}</flux:text>
            </div>
            <div class="w-1/2">
                <flux:input label="Date" type="date" wire:model.live="date" />

            </div>
            </div>
            <flux:table :paginate="$this->walletLogs">
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Previews</flux:table.column>
                    <flux:table.column>Current</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($this->walletLogs as $log)
                        <flux:table.row :key="$log->id">
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-neutral-500">{{ $log->created_at->format('h:i A') }}
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold">{{ $log->description }}</div>
                                    <div class="text-xs text-{{$log->type->color()}}-500">{{ $log->type->label() }}</div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell variant="strong">{{ number_format($log->amount,2) }}</flux:table.cell>
                            <flux:table.cell variant="strong">{{ number_format($log->previous_balance,2) }}</flux:table.cell>
                            <flux:table.cell variant="strong">{{ number_format($log->current_balance,2) }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
   @endif
</flux:modal>
