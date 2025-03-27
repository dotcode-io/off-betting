<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateManualRefAction;
use Illuminate\Http\Request;

final class GenerateManualRefController
{
    public function store(CreateManualRefAction $action, Request $request)
    {
        $request->validate([
            'number_generate' => 'required|integer|min:1',
        ]);

        $numberGenerate = $request->integer('number_generate');

        $refs = $action->handle($numberGenerate);

        return response()->json($refs, 201);
    }
}
