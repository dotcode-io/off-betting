<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class BetForm extends Form
{
    #[Validate('required', 'string')]
    public $amount = '';
    #[Validate('required', 'string','in:meron,wala,draw')]
    public $side = '';
}
