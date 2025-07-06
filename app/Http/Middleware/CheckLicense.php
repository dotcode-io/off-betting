<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip license check for license renewal command and certain routes
        if ($this->shouldSkipLicenseCheck($request)) {
            return $next($request);
        }

        // Check if current license is valid
        if (! License::isCurrentLicenseValid()) {
            $licenseStatus = License::getLicenseStatus();

            // Return JSON response for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Application license has expired',
                    'message' => 'The application license for '.$licenseStatus['current_month'].' is not valid. Please renew your license.',
                    'license_status' => $licenseStatus,
                ], 403);
            }

            // Return view for web requests
            return response()->view('license-expired', [
                'licenseStatus' => $licenseStatus,
            ], 403);
        }

        return $next($request);
    }

    /**
     * Determine if license check should be skipped for certain routes
     */
    private function shouldSkipLicenseCheck(Request $request): bool
    {
        // Skip for artisan commands (when running in CLI)
        if (app()->runningInConsole()) {
            return true;
        }

        // Skip for license-related routes
        $skipRoutes = [
            'license/status',
            'license/expired',
        ];

        $currentPath = $request->path();

        foreach ($skipRoutes as $route) {
            if (str_starts_with($currentPath, $route)) {
                return true;
            }
        }

        return false;
    }
}
