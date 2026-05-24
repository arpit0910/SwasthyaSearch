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
     * City => scraper class map.
     *
     * @var array<string, class-string>
     */
    private const CITY_SCRAPER_MAP = [
        'jaipur' => JaipurScraperService::class,
        'delhi' => DelhiScraperService::class,
        'jodhpur' => JodhpurScraperService::class,
        'kota' => KotaScraperService::class,
        'mumbai' => MumbaiScraperService::class,
        'pune' => PuneScraperService::class,
        'bangalore' => BangaloreScraperService::class,
        'hyderabad' => HyderabadScraperService::class,
        'ahmedabad' => AhmedabadScraperService::class,
        'kolkata' => KolkataScraperService::class,
        'chennai' => ChennaiScraperService::class,
    ];

    /**
     * Get the appropriate scraper service class for a given city.
     */
    private static function getServiceForCity(string $city): ?string
    {
        $cityClean = strtolower(trim($city));

        return self::CITY_SCRAPER_MAP[$cityClean] ?? null;
    }

    /**
     * List all supported city names in title case.
     *
     * @return array<int, string>
     */
    public static function getSupportedCities(): array
    {
        return array_map(
            static fn (string $city): string => ucwords($city),
            array_keys(self::CITY_SCRAPER_MAP)
        );
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
            $bloodBanks = $service::scrapeBloodBanks($city, $forceFallback, $customUrl, $cacheKey);
            
            $syncedIds = [];
            foreach ($bloodBanks as $bb) {
                $record = \App\Models\BloodBank::updateOrCreate(
                    ['name_en' => $bb['name_en'], 'city' => $bb['city']],
                    $bb
                );
                $syncedIds[] = $record->id;
            }

            if (!empty($bloodBanks) && !empty($syncedIds)) {
                \App\Models\BloodBank::where('city', 'LIKE', "%{$city}%")->whereNotIn('id', $syncedIds)->delete();
            }

            return $bloodBanks;
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
