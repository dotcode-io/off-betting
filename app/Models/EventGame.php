<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ResultEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EventGame extends Model
{
    use HasFactory;

    public $casts = [
        'result' => ResultEnum::class,
    ];
}
