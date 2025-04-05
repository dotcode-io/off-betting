<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Profile;

use App\Livewire\Actions\Logout;
use App\Models\User;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class Settings extends Component
{
    public bool $showShutdownConfirmation = false;

    public ?string $shutdownPassword = null;

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

    public function confirmShutdown(): void
    {
        /** @var ?User $user */
        $user = Auth::user();
        if (! $user?->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        $this->showShutdownConfirmation = true;
    }

    public function cancelShutdown(): void
    {
        $this->showShutdownConfirmation = false;
        $this->shutdownPassword = null;
    }

    public function shutdownServer(): void
    {
        /** @var ?User $user */
        $user = Auth::user();
        if (! $user?->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (! $this->shutdownPassword) {
            Flux::toast('Password is required for shutdown', variant: 'danger');

            return;
        }

        if (! Hash::check($this->shutdownPassword, $user->password)) {
            Flux::toast('Invalid password', variant: 'danger');

            return;
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('host:shutdown');
            Flux::modal('showShutdownConfirmation')->close();
            Flux::toast('Server shutdown initiated!', variant: 'success');
        } catch (Exception $e) {
            Flux::toast('Failed to shutdown server: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.dashboard.profile.settings');
    }
}
