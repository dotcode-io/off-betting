<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Profile;

use App\Livewire\Actions\Logout;
use Livewire\Component;

final class Settings extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/login', navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.dashboard.profile.settings');
    }
}
