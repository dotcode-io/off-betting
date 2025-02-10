<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Console\BetAction;
use App\DataTransferObjects\BettingDataTransferObject;
use App\Livewire\Forms\BetForm;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class BettingController
{
    public BetForm $betForm;

    public function store(BetAction $actions, Request $request): Response
    {
        $request->validate([
            'amount' => 'required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/',
            'side' => 'required', 'string', 'in:meron,wala,draw',
        ]);
        $event = Event::getCurrent();
        $actions->handle($event, BettingDataTransferObject::fromArray($request->only('amount', 'side')));

        return response(status: 201);
    }
}
