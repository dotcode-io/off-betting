<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class OpenGameForm extends Form
{
    public string $meron_name = '-';

    public string $wala_name = '-';
}
