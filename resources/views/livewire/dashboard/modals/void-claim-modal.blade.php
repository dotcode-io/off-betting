<flux:modal name="void-claim-modal" class="min-w-[22rem] space-y-6">
    @if(isset($bet))
        <div class="space-y-4">
            <flux:heading size="lg">Void Transaction</flux:heading>

            <flux:subheading>
                <p>Are you sure you want to void this transaction?</p>
                <p class="text-sm text-gray-500 mt-2">Reference: {{ $bet->reference_no }}</p>
            </flux:subheading>
        </div>

        <form wire:submit="voidClaim">
            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger">Yes, Void</flux:button>
            </div>
        </form>
    @endif
</flux:modal>
