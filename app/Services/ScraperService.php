<?php

namespace App\Services;

use App\Services\JaipurScraperService;
use App\Services\DelhiScraperService;
use App\Services\JodhpurScraperService;
use App\Services\KotaScraperService;
use App\Services\MumbaiScraperService;
use App\Services\PuneScraperService;
use App\Services\BangaloreScraperService;
use App\Services\HyderabadScraperService;
use App\Services\AhmedabadScraperService;
use App\Services\KolkataScraperService;
use App\Services\ChennaiScraperService;
use Illuminate\Support\Facades\Log;

class ScraperService
{
    /**
     * Get the appropriate scraper service class for a given city.
     */
    private static function getServiceForCity(string $city): ?string
    {
        $cityClean = strtolower(trim($city));

        return match ($cityClean) {
            'jaipur' => JaipurScraperService::class,
            'delhi', 'new delhi' => DelhiScraperService::class,
            'jodhpur' => JodhpurScraperService::class,
            'kota' => KotaScraperService::class,
            'mumbai' => MumbaiScraperService::class,
            'pune' => PuneScraperService::class,
            'bangalore', 'bengaluru' => BangaloreScraperService::class,
            'hyderabad' => HyderabadScraperService::class,
            'ahmedabad' => AhmedabadScraperService::class,
            'kolkata', 'calcutta' => KolkataScraperService::class,
            'chennai', 'madras' => ChennaiScraperService::class,
            default => null,
        };
    }

    /**
     * Scrape and synchronize doctors for a given city.
     */
    public static function scrapeDoctors(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $service = self::getServiceForCity($city);

        if ($service) {
            return $service::scrapeDoctors($city, $forceFallback, $customUrl, $cacheKey);
        }

        Log::warning("No specific scraper service implemented yet for city: {$city}");
        return [];
    }

    /**
     * Scrape and synchronize hospitals for a given city.
     */
    public static function scrapeHospitals(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $service = self::getServiceForCity($city);

        if ($service) {
            return $service::scrapeHospitals($city, $forceFallback, $customUrl, $cacheKey);
        }

        Log::warning("No specific scraper service implemented yet for city: {$city}");
        return [];
    }

    /**
     * Scrape and synchronize blood banks for a given city.
     */
    public static function scrapeBloodBanks(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $service = self::getServiceForCity($city);

        if ($service) {
            return $service::scrapeBloodBanks($city, $forceFallback, $customUrl, $cacheKey);
        }

        Log::warning("No specific scraper service implemented yet for city: {$city}");
        return [];
    }

    /**
     * Get real degrees for a department.
     */
    public static function getRealDegreesForDepartment(string $deptEn): array
    {
        return JaipurScraperService::getRealDegreesForDepartment($deptEn);
    }
}
