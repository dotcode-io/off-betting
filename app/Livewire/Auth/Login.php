<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.guest')]
final class Login extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        if ($user->isAdmin()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }

        if ($user->isTeller()) {
            $this->redirectIntended(default: route('teller.dashboard', absolute: false), navigate: true);
        }

        if ($user->isController()) {
            $this->redirectIntended(default: route('controller.dashboard', absolute: false), navigate: true);
        }        
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.login');
    }
}
