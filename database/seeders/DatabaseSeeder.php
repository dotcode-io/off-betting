<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AppSetting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'admin',
            'user_type' => 'admin',
            Hash::make('password'),
            'wallet_amount' => 0,
            'commission_amount' => 0,

        ]);

        User::create([
            'username' => 'teller',
            'user_type' => 'teller',
            Hash::make('password'),
            'wallet_amount' => 0,
            'commission_amount' => 0,
        ]);

        User::create([
            'username' => 'controller',
            'user_type' => 'controller',
            Hash::make('password'),
            'wallet_amount' => 0,
            'commission_amount' => 0,
        ]);

        AppSetting::create([
            'app_name' => 'OCBS - KCI Gaming Services',
        ]);
    }
}
