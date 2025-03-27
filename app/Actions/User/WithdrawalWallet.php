<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use App\Models\WalletLog;
use Exception;
use Illuminate\Support\Facades\DB;

final class WithdrawalWallet
{
    public function handle(User $user, array $attributes): User
    {
        return DB::transaction(function () use ($user, $attributes): User {

            $update = User::query()
                ->where('id', $user->id)
                ->where('version', $user->version)
                ->update([
                    'wallet_amount' => $user->wallet_amount - $attributes['amount'],
                    'version' => $user->version + 1,
                ]);

            if ($update === 0) {
                throw new Exception('Failed to update wallet');
            }

            WalletLog::query()->create([
                'user_id' => $user->id,
                'amount' => $attributes['amount'],
                'type' => 'debit',
                'description' => $attributes['description'],
                'previous_balance' => $user->wallet_amount,
                'current_balance' => $user->wallet_amount - $attributes['amount'],
            ]);

            $user->wallet_amount -= $attributes['amount'];

            return $user;
        });

    }
}
