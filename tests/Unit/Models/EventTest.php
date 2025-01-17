<?php

declare(strict_types=1);

test('to array', function () {
    $event = App\Models\Event::factory()->create()->refresh();

    expect(array_keys($event->toArray()))
        ->toBe([
            'id',
            'uuid',
            'name',
            'date',
            'start_of_game',
            'number_of_games',
            'status',
            'opened_at',
            'closed_at',
            'created_at',
            'updated_at',
        ]);
});
