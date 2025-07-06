<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'license_key',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if the current license is valid for the current month
     */
    public static function isCurrentLicenseValid(): bool
    {
        return self::isCurrentLicenseValidWithTimeCheck();
    }

    /**
     * Check if the current license is valid considering time-based locking
     * Locking activates at 8 AM on the first day of each month
     */
    public static function isCurrentLicenseValidWithTimeCheck(): bool
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $currentDay = $now->day;
        $currentHour = $now->hour;

        // Check if we have a valid license for the current month
        $currentLicense = self::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('is_active', true)
            ->where('expires_at', '>', $now)
            ->first();

        // If we have a valid license for current month, allow access
        if ($currentLicense !== null) {
            return true;
        }

        // If no current month license exists, check time-based rules
        // Only allow access before 8 AM on the first day of the month
        // if previous month had a valid license
        if ($currentDay === 1 && $currentHour < 8) {
            $previousMonth = $now->copy()->subMonth();

            $previousLicense = self::where('month', $previousMonth->month)
                ->where('year', $previousMonth->year)
                ->where('is_active', true)
                ->first();

            // Allow access only if previous month had a valid license
            return $previousLicense !== null;
        }

        // For all other cases (8 AM or later on first day, or any other day),
        // deny access if no current month license exists
        return false;
    }

    /**
     * Get the current active license
     */
    public static function getCurrentLicense(): ?self
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return self::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('is_active', true)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    /**
     * Generate a new license for the current month
     */
    public static function generateCurrentMonthLicense(): self
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Deactivate any existing license for this month/year
        self::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->update(['is_active' => false]);

        // Create new license
        $licenseKey = self::generateLicenseKey($currentMonth, $currentYear);
        $expiresAt = Carbon::now()->endOfMonth();

        return self::create([
            'month' => $currentMonth,
            'year' => $currentYear,
            'license_key' => $licenseKey,
            'is_active' => true,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Get license status information
     */
    public static function getLicenseStatus(): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentMonthName = Carbon::now()->format('F Y');

        $license = self::getCurrentLicense();

        return [
            'current_month' => $currentMonthName,
            'is_valid' => $license !== null,
            'license_key' => $license?->license_key,
            'expires_at' => $license?->expires_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate a unique license key
     */
    private static function generateLicenseKey(int $month, int $year): string
    {
        $monthName = Carbon::createFromDate($year, $month, 1)->format('M');
        $hash = hash('sha256', $month.$year.time().config('app.key'));

        return mb_strtoupper($monthName.$year.'-'.mb_substr($hash, 0, 8));
    }
}
