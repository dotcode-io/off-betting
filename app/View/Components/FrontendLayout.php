<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
    public function render(): View
    {

        // check if the routes has /admin

        if (Auth::check()) {
            if (request()->is('app/*')) {
                return view('components.admin-layout');
            }

            return view('components.frontend-layout');
        }

        return view('components.guest');

    }
}
