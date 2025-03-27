<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Actions\User\DepositWallet;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

final class AddWalletModal extends Component
{
    public ?User $user = null;

    public $amount = '';

    #[On('add-wallet')]
    public function openModal(int $id): void
    {
        $this->user = User::findOrFail($id);

        Flux::modal('add-wallet-modal')->show();
    }

    public function save(DepositWallet $action): void
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/'],
        ]);

        $action->handle($this->user, [
            'amount' => $this->amount,
            'description' => 'Added wallet',
        ]);

        Flux::toast('Wallet successfully added!', variant: 'success');
        Flux::modal('add-wallet-modal')->close();

        $this->dispatch('user-refresh');
        $this->amount = '';

    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.add-wallet-modal');
    }
}
