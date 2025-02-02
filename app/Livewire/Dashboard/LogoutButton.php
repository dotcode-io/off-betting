<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Livewire\Actions\Logout;
use Livewire\Component;

final class LogoutButton extends Component
{
    public function logout(Logout $logout)
    {
        $logout();

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.dashboard.logout-button');
    }
}
