<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OpenGameForm extends Form
{
    #[Validate('required', 'string')]
    public string $meron_name = '';

    #[Validate('required', 'string')]
    public string $wala_name = '';

}
