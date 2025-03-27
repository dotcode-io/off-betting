<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ManualRef;

final class CreateManualRefAction
{
    public function handle(int $numberGenerate): array
    {
        $start = ManualRef::query()->count() + 1;

        $refs = [];
        for ($i = $start; $i <= $numberGenerate; $i++) {
            $refs[] = [
                'ref' => 'M-'.mb_str_pad((string) $i, 6, '0', STR_PAD_LEFT),
            ];
        }

        ManualRef::query()->insert($refs);

        return $refs;

    }
}
