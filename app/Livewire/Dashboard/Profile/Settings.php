<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Profile;

use App\Livewire\Actions\Logout;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class Settings extends Component
{
    #[Validate('required|min:6')]
    public $current_password;

    #[Validate('required|min:6')]
    public $password;

    #[Validate('required|min:6|same:password')]
    public $confirm_password;

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/login', navigate: true);
    }

    public function save(): void
    {
        $this->validate();

        if (! Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'The current password is incorrect.');

            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        Flux::toast('Password changed successfully!', variant: 'success');

        $this->logout(new Logout());
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.dashboard.profile.settings');
    }
}
