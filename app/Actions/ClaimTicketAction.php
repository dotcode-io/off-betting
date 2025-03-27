<?php

declare(strict_types=1);

namespace App\Actions;

use App\Actions\User\WithdrawalWallet;
use App\Models\Bet;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class ClaimTicketAction
{
    public function __construct(public WithdrawalWallet $remitWalletAction)
    {
        //
    }

    public function handle(Bet $ticket): void
    {
        if ($ticket->is_claimed || $ticket->win_amount <= 0) {
            throw new Exception('Ticket already claimed or no winnings');
        }

        $ticket->load('user');

        DB::transaction(function () use ($ticket) {
            $teller = Auth::user();

            if ($teller->wallet_amount < $ticket->win_amount) {
                throw new Exception('Insufficient funds');
            }

            $this->remitWalletAction->handle($teller, [
                'amount' => $ticket->win_amount,
                'description' => 'Claimed ticket',
            ]);

            $ticket->update([
                'is_claimed' => 1,
                'claimed_by' => auth()->id(),
                'claimed_at' => now(),
            ]);

        });
    }
}
