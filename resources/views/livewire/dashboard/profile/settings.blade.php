<div>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1" class="mb-6">Settings</flux:heading>
        </div>
    </div>
    <flux:separator variant="subtle" />
    <div class="mt-4">
         <flux:card class="w-1/3 pb-4 mb-4">
            <div>
                <flux:heading size="lg" class="mb-4">Theme</flux:heading>
            </div>
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </flux:card>

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
                    <flux:button type="submit">Save Changes</flux:button>
                </div>
            </form>
        </flux:card>

        @if(\Illuminate\Support\Facades\Auth::user()?->isAdmin())
        <flux:card class="w-1/3 mt-4">
            <div>
                <flux:heading size="lg" class="mb-4">Server Management</flux:heading>
                <p class="text-red-600 dark:text-red-400 mb-4">Warning: This action will shutdown the host server.</p>
                <flux:button
                    wire:click="confirmShutdown"
                    variant="danger"
                    icon="power"
                    class="w-full"
                >Shutdown Host Server</flux:button>
            </div>
        </flux:card>
        @endif
    </div>

    <flux:modal wire:model="showShutdownConfirmation">
            <div>
                <flux:heading size="lg">Confirm Shutdown</flux:heading>
                <flux:text class="mt-2">Are you sure you want to shutdown the host server? This action cannot be undone and will terminate all services.</flux:text>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Please enter your password to confirm this action:</p>
            <flux:input
                type="password"
                placeholder="Enter your password"
                wire:model="shutdownPassword"
                class="w-full"
                viewable
            />

            <div class="flex mt-4 space-x-2">
                <flux:spacer />
                <flux:button wire:click="cancelShutdown" >Cancel</flux:button>
                <flux:button wire:click="shutdownServer" variant="danger">Yes, Shutdown Server</flux:button>
            </div>
           
    </flux:modal>
</div>