<?php

namespace App\Services;

use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReliableHealthcareDirectoryService
{
    /**
     * Retrieve verified doctors by city with optional department filtering.
     * Automatically triggers live scraping or verified data enrichment if no records exist.
     *
     * @param string $city
     * @param string|null $department
     * @return Collection
     */
    public static function doctorsByCity(string $city, ?string $department = null): Collection
    {
        $cleanCity = trim($city);

        $query = Doctor::with(['department', 'departments', 'hospitals'])
            ->where('is_verified', true)
            ->where(function ($q) use ($cleanCity) {
                $q->where('city', 'LIKE', "%{$cleanCity}%")
                  ->orWhereHas('hospitals', function ($hq) use ($cleanCity) {
                      $hq->where('city', 'LIKE', "%{$cleanCity}%");
                  });
            });

        if (!empty($department)) {
            $cleanDept = trim($department);
            $query->where(function ($q) use ($cleanDept) {
                $q->whereHas('department', function ($dq) use ($cleanDept) {
                    $dq->where('name_en', 'LIKE', "%{$cleanDept}%")
                       ->orWhere('name_hi', 'LIKE', "%{$cleanDept}%");
                })->orWhereHas('departments', function ($dq) use ($cleanDept) {
                    $dq->where('name_en', 'LIKE', "%{$cleanDept}%")
                       ->orWhere('name_hi', 'LIKE', "%{$cleanDept}%");
                });
            });
        }

        $doctors = $query->get();

        // If no records found, attempt live scraping / verified source enrichment once
        if ($doctors->isEmpty()) {
            try {
                Log::info("No verified doctors found for city: {$cleanCity}. Initiating ScraperService enrichment...");
                ScraperService::scrapeDoctors($cleanCity, false);
                $doctors = $query->get();
            } catch (\Exception $e) {
                Log::error("Error during live doctor scraping for {$cleanCity}: " . $e->getMessage());
            }
        }

        return $doctors;
    }

    /**
     * Retrieve verified hospitals by city.
     * Automatically triggers live scraping or verified data enrichment if no records exist.
     *
     * @param string $city
     * @return Collection
     */
    public static function hospitalsByCity(string $city): Collection
    {
        $cleanCity = trim($city);

        $query = Hospital::where('is_verified', true)
            ->where('city', 'LIKE', "%{$cleanCity}%");

        $hospitals = $query->get();

        if ($hospitals->isEmpty()) {
            try {
                Log::info("No verified hospitals found for city: {$cleanCity}. Initiating ScraperService enrichment...");
                ScraperService::scrapeHospitals($cleanCity, false);
                $hospitals = $query->get();
            } catch (\Exception $e) {
                Log::error("Error during live hospital scraping for {$cleanCity}: " . $e->getMessage());
            }
        }

        return $hospitals;
    }

    /**
     * Retrieve verified blood banks by city.
     * Automatically triggers live scraping or verified data enrichment if no records exist.
     *
     * @param string $city
     * @return Collection
     */
    public static function bloodBanksByCity(string $city): Collection
    {
        $cleanCity = trim($city);

        $query = BloodBank::where('is_verified', true)
            ->where('city', 'LIKE', "%{$cleanCity}%");

        $bloodBanks = $query->get();

        if ($bloodBanks->isEmpty()) {
            try {
                Log::info("No verified blood banks found for city: {$cleanCity}. Initiating ScraperService enrichment...");
                ScraperService::scrapeBloodBanks($cleanCity, false);
                $bloodBanks = $query->get();
            } catch (\Exception $e) {
                Log::error("Error during live blood bank scraping for {$cleanCity}: " . $e->getMessage());
            }
        }

        return $bloodBanks;
    }
}
