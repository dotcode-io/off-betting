<flux:card>
    <form wire:submit='login' class="space-y-6">
        <div>
            <flux:heading size="lg">Login to your account</flux:heading>
            <flux:subheading>Welcome back!</flux:subheading>
        </div>

        <div class="space-y-5">
            <flux:input wire:model='form.username' label="Username" type="text" placeholder="Enter your username" />
            <flux:input wire:model='form.password' label="Password" type="password" placeholder="Enter your password" viewable />

            <flux:checkbox wire:model="form.remember" label="Remember me" />
        </div>

        <div class="space-y-2">
            <flux:button variant="primary" class="w-full" type="submit">Login</flux:button>

        </div>
    </form>
</flux:card>