<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Summary</flux:heading>
        </div>

    </div>
    <flux:separator variant="subtle" />

    <div class="pt-2">
        <flux:text>Current Wallet</flux:text>
        <flux:heading size="xl" class="mb-1 text-green-500">
            {{ number_format($wallet, '2','.',',') }}
        </flux:heading>

    </div>
</div>
