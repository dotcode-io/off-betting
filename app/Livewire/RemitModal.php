<?php

namespace App\Livewire;

use App\Actions\User\WithdrawalWallet;
use App\Models\User;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class RemitModal extends Component
{
    public ?User $user = null;

    public $amount = '';

    public function save(WithdrawalWallet $action): void
    {

        $this->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/'],
        ]);

        $action->handle($this->user, [
            'amount' => $this->amount,
            'description' => 'Remitted wallet',
        ]);

        Flux::toast('Wallet successfully remitted!', variant: 'success');
        Flux::modal('remit-modal')->close();

        $this->dispatch('user-refresh');
        $this->amount = '';
    }

    #[On('remit')]
    public function openModal(int $id): void
    {
        $this->user = User::findOrFail($id);
        $this->amount = $this->user->wallet_amount;

        Flux::modal('remit-modal')->show();
    }
    public function render(): View
    {
        return view('livewire.remit-modal');
    }
}
