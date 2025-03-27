<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ManualRef extends Model
{
    protected $casts = [
        'used' => 'boolean',
    ];
}
