<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class OpenGameForm extends Form
{
    #[Validate('required', 'string')]
    public string $meron_name = '';

    #[Validate('required', 'string')]
    public string $wala_name = '';
}
