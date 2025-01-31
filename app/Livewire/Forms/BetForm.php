<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

final class BetForm extends Form
{
    #[Validate('required', 'numeric', 'min:1', 'max:100000', 'regex:/^\d*(\.\d{1,2})?$/')]
    public $amount = '';

    #[Validate('required', 'string', 'in:meron,wala,draw')]
    public $side = '';
}
