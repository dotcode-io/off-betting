<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Events\SideOpenEvent;
use Illuminate\Support\Facades\Cache;

final class UpdateBetSideAction
{
    public function handle(string $side): void
    {

        $value = Cache::get('open_'.$side, 1);

        if ($value === 1) {
            Cache::put('open_'.$side, 0);
        }


    }
}
