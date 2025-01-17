<?php

declare(strict_types=1);

test('to array', function () {
    $user = App\Models\User::factory()->create()->refresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'uuid',
            'username',
            'wallet_amount',
            'commission_amount',
            'user_type',
            'version',
            'created_at',
            'updated_at',
        ]);
});
