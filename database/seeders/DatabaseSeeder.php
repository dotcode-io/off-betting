<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AppSetting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'user_type' => 'admin',
        ]);

        User::factory()->create([
            'username' => 'teller',
            'user_type' => 'teller',
        ]);

        User::factory()->create([
            'username' => 'controller',
            'user_type' => 'controller',
        ]);

        AppSetting::create([
            'app_name' => 'OCBS - KCI Gaming Services',
        ]);
    }
}
