<?php

declare(strict_types=1);

test('test guest layout', function () {

    // test the FrontendLayout component

    $component = new App\View\Components\FrontendLayout();

    expect($component->render())->toBeInstanceOf(Illuminate\View\View::class);
});
