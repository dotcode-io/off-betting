<?php

declare(strict_types=1);

test('to array', function () {
    $ledger = App\Models\Ledger::factory()->create()->refresh();

    expect(array_keys($ledger->toArray()))
        ->toBe([
            'id',
            'user_id',
            'sender_id',
            'receiver_id',
            'transact_by_id',
            'type',
            'transaction_date',
            'description',
            'debit',
            'credit',
            'balance',
            'status',
            'created_at',
            'updated_at',
        ]);
});
