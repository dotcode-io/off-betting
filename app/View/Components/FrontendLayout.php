<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class FrontendLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // check if the routes has /admin

        if (request()->is('admin/*')) {
            return view('components.admin-layout');
        }

        return view('components.frontend-layout');
    }
}
