<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class Bet extends Model
{
    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
