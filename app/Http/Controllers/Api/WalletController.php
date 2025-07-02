<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

final class WalletController
{
    public function index()
    {
        $user = Auth::user();

        return response([
            'balance' => $user->wallet_amount,
        ], 200);
    }
}
