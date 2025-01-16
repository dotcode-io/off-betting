<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->environment('production'),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        // Model::shouldBeStrict();

        Model::unguard();
    }

    /**
     * Configure the application's URL.
     */
    private function configureUrl(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
