<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class AppSetting extends Model
{
    public static function getCache(): self
    {
        return Cache::remember('app_setting', (60 * 60 * 60), function () {
            return AppSetting::first();
        });
    }
}
