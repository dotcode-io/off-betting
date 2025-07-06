<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\License;
use Carbon\Carbon;

class LicenseRenewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:renew
                            {--show-status : Show current license status}
                            {--force : Force renewal even if current license is valid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew or generate application license for the current month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Application License Manager');
        $this->info('========================');

        // Show current status if requested
        if ($this->option('show-status')) {
            $this->showLicenseStatus();
            return;
        }

        $currentMonth = Carbon::now()->format('F Y');
        $this->info("Processing license for: {$currentMonth}");

        // Check if current license is already valid
        if (License::isCurrentLicenseValid() && !$this->option('force')) {
            $this->warn('Current license is already valid for this month.');
            $this->info('Use --force option to renew anyway, or --show-status to view details.');
            return;
        }

        try {
            // Generate new license
            $this->info('Generating new license...');
            $license = License::generateCurrentMonthLicense();

            $this->info('✅ License renewed successfully!');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Month/Year', Carbon::createFromDate($license->year, $license->month, 1)->format('F Y')],
                    ['License Key', $license->license_key],
                    ['Expires At', $license->expires_at->format('Y-m-d H:i:s')],
                    ['Status', $license->is_active ? 'Active' : 'Inactive'],
                ]
            );

        } catch (\Exception $e) {
            $this->error('❌ Failed to renew license: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Show current license status
     */
    private function showLicenseStatus()
    {
        $status = License::getLicenseStatus();

        $this->info('Current License Status:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Current Month', $status['current_month']],
                ['License Valid', $status['is_valid'] ? '✅ Yes' : '❌ No'],
                ['License Key', $status['license_key'] ?? 'N/A'],
                ['Expires At', $status['expires_at'] ?? 'N/A'],
            ]
        );

        if (!$status['is_valid']) {
            $this->warn('⚠️  Application is currently LOCKED due to invalid license.');
            $this->info('Run "php artisan license:renew" to generate a new license.');
        }
    }
}
