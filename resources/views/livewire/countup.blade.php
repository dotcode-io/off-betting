<?php

use Livewire\Volt\Component;

new class extends Component {
    public $value;
}; ?>
<span x-countup="{{ $value }}"></span>
