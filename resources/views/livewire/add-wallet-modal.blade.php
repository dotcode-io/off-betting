<flux:modal name="add-wallet-modal" class="md:w-96">
<div class="space-y-6">
    <div>
        <flux:heading size="lg">Add Balance</flux:heading>
        <flux:text class="mt-2">Adding balance to the box.</flux:text>
    </div>
    <flux:input label="Amount" placeholder="Enter Amount" wire:model="amount"/>
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:click="save()">Add now</flux:button>
    </div>
</div>
</flux:modal>
