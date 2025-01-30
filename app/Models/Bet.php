<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Bet extends Model
{
    use HasUuids;


    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
