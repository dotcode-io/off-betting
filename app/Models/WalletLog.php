<?php

namespace App\Models;

use App\Enums\WalletLogType;
use Illuminate\Database\Eloquent\Model;

class WalletLog extends Model
{
    public $casts = [
        'created_at' => 'datetime',
        'type' => WalletLogType::class,
    ];
    public function dateFormatted(): string
    {
        return $this->created_at->format('m/d/Y H:i:s');
    }
}
