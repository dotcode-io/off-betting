<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Modals;

use App\Actions\User\DepositWallet;
use App\Models\Bet;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class VoidClaimModal extends Component
{
    public Bet $bet;

    #[On('void-claim')]
    public function openModal(int $betId): void
    {
        try {
            $this->bet = Bet::query()->with('user')->findOrFail($betId);
        } catch (Throwable $e) {
            Flux::toast('Bet not found!', variant: 'danger');

            return;
        }

        Flux::modal('void-claim-modal')->show();
    }

    public function voidClaim(DepositWallet $action): void
    {
        try {
            $this->bet->update([
                'is_claimed' => 0,
                'claimed_by' => null,
                'claimed_at' => null,
            ]);

            // Only deposit wallet for teller claims (when claimed_by matches current user)
            $action->handle($this->bet->user, [
                'amount' => $this->bet->bet_amount,
                'description' => 'Voided claim',
            ]);

            $this->dispatch('refreshClaimHistory');
            Flux::modal('void-claim-modal')->close();
            Flux::toast('Transaction voided successfully', variant: 'success');
        } catch (Throwable $e) {
            Flux::toast('Failed to void transaction', variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.modals.void-claim-modal');
    }
}
