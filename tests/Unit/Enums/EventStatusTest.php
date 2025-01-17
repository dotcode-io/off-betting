<?php

declare(strict_types=1);

test('test all case has label', function () {

    $cases = App\Enums\EventStatus::cases();
    expect($cases)->each(fn ($case) => $case->label())->not()->toBeNull();

});
