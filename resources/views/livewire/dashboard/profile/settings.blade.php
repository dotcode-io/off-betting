<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Settings</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="mt-4">
        <flux:card class="w-1/3">
            <form wire:submit='save'>
                <div>
                    <flux:heading size="lg" class="mb-4">Update Password</flux:heading>
                </div>

                <flux:input label="Current Password" type="password" placeholder="Enter current password" wire:model="current_password" class="mb-3" viewable />
                <flux:input label="New Password" type="password" placeholder="Enter new password" wire:model="password" class="mb-3" viewable />
                <flux:input label="Confirm Password" type="password" placeholder="Confirm new password" wire:model="confirm_password" class="mb-3" viewable />
                <div class="flex pt-2">
                    <flux:spacer />
                    <flux:button type="submit">Update</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</div>