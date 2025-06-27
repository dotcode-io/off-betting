<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use App\Models\WalletLog;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

final class ViewWalletModal extends Component
{
    use WithoutUrlPagination,WithPagination;

    public ?User $user = null;

    public $date = '';

    #[On('view-wallet')]
    public function view(int $id)
    {

        $this->resetPage();

        $this->date = date('Y-m-d');
        $this->user = User::findOrFail($id);

        Flux::modal('view-wallet-modal')->show();

    }

    #[\Livewire\Attributes\Computed]
    public function walletLogs()
    {
        return WalletLog::query()
            ->where('user_id', $this->user->id)
            ->whereDate('created_at', $this->date)
            ->latest()
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.view-wallet-modal');
    }
}
