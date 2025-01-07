<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<flux:card>
    <form wire:submit='login' class="space-y-6">
        <div>
            <flux:heading size="lg">Log in to your account</flux:heading>
            <flux:subheading>Welcome back!</flux:subheading>
        </div>

        <div class="space-y-6">
            <flux:input wire:model='form.email' label="Email" type="email" placeholder="Your email address" />

            <flux:field>
                <flux:label class="flex justify-between">
                    Password

                </flux:label>

                <flux:input wire:model='form.password' type="password" placeholder="Your password" />

                <flux:error name="form.password" />
            </flux:field>

            <flux:checkbox wire:model="form.remember" label="Remember me" />
        </div>

        <div class="space-y-2">
            <flux:button variant="primary" class="w-full" type="submit">Log in</flux:button>

        </div>
    </form>
</flux:card>
