<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard\Teller;

use Livewire\Component;

final class Summary extends Component
{
    public function render()
    {
        $wallet = auth()->user()->wallet_amount;
        return view('livewire.dashboard.teller.summary',[
            'wallet' => $wallet
        ]);
    }
}
