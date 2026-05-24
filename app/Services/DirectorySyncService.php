<?php

namespace App\Services;

use App\Models\DirectorySyncHistory;
use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Facades\Cache;
use Throwable;

class DirectorySyncService
{
    public const PROGRESS_KEY = 'scrape_progress_directory_all';

    /**
     * Run full sync for hospitals, doctors and blood banks for one city.
     *
     * @return array<string, mixed>
     */
    public function syncCity(string $city, bool $forceFallback = false, ?string $cacheKey = null): array
    {
        $progressKey = $cacheKey ?: self::PROGRESS_KEY;
        $cityTitle = ucwords(trim($city));

        $initialCounts = $this->getCountsByCity($cityTitle);
        $startedAt = now();
        $this->putProgress($progressKey, $cityTitle, 5, 'running', 'Starting full directory sync...');

        $lock = Cache::lock('sync_directory_all_' . strtolower($cityTitle), 1200);
        if (!$lock->get()) {
            $busy = [
                'status' => 'busy',
                'city' => $cityTitle,
                'message' => "A sync is already in progress for {$cityTitle}.",
            ];

            $this->storeHistory($cityTitle, $forceFallback, $initialCounts, $initialCounts, [
                'hospitals' => 0,
                'doctors' => 0,
                'blood_banks' => 0,
            ], $busy, $startedAt, now());
            return $busy;
        }

        try {
            $this->putProgress($progressKey, $cityTitle, 20, 'running', 'Syncing hospitals...');
            $hospitals = ScraperService::scrapeHospitals($cityTitle, $forceFallback, null, $progressKey);

            $this->putProgress($progressKey, $cityTitle, 50, 'running', 'Syncing doctors...');
            $doctors = ScraperService::scrapeDoctors($cityTitle, $forceFallback, null, $progressKey);

            $this->putProgress($progressKey, $cityTitle, 80, 'running', 'Syncing blood banks...');
            $bloodBanks = ScraperService::scrapeBloodBanks($cityTitle, $forceFallback, null, $progressKey);

            $finalCounts = $this->getCountsByCity($cityTitle);

            $report = [
                'status' => 'completed',
                'city' => $cityTitle,
                'fetched' => [
                    'hospitals' => count($hospitals),
                    'doctors' => count($doctors),
                    'blood_banks' => count($bloodBanks),
                ],
                'db_counts_before' => $initialCounts,
                'db_counts_after' => $finalCounts,
                'changes' => [
                    'hospitals' => $finalCounts['hospitals'] - $initialCounts['hospitals'],
                    'doctors' => $finalCounts['doctors'] - $initialCounts['doctors'],
                    'blood_banks' => $finalCounts['blood_banks'] - $initialCounts['blood_banks'],
                ],
                'synced_at' => now()->toDateTimeString(),
                'message' => "Directory synced successfully for {$cityTitle}.",
            ];

            Cache::put('last_directory_sync_report_' . strtolower($cityTitle), $report, now()->addDay());
            $this->putProgress($progressKey, $cityTitle, 100, 'completed', $report['message'], $report);
            $this->storeHistory($cityTitle, $forceFallback, $initialCounts, $finalCounts, $report['fetched'], $report, $startedAt, now());

            return $report;
        } catch (Throwable $e) {
            $error = [
                'status' => 'failed',
                'city' => $cityTitle,
                'message' => 'Sync failed: ' . $e->getMessage(),
            ];
            $this->putProgress($progressKey, $cityTitle, 100, 'failed', $error['message'], $error);
            $this->storeHistory($cityTitle, $forceFallback, $initialCounts, $initialCounts, [
                'hospitals' => 0,
                'doctors' => 0,
                'blood_banks' => 0,
            ], $error, $startedAt, now());
            return $error;
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * @return array<string, int>
     */
    private function getCountsByCity(string $city): array
    {
        return [
            'hospitals' => Hospital::where('city', 'LIKE', "%{$city}%")->count(),
            'doctors' => Doctor::where('city', 'LIKE', "%{$city}%")->count(),
            'blood_banks' => BloodBank::where('city', 'LIKE', "%{$city}%")->count(),
        ];
    }

    /**
     * @param array<string, mixed>|null $report
     */
    private function putProgress(string $cacheKey, string $city, int $progress, string $status, string $message, ?array $report = null): void
    {
        $payload = [
            'status' => $status,
            'city' => $city,
            'progress' => $progress,
            'message' => $message,
            'updated_at' => now()->toDateTimeString(),
        ];

        if ($report !== null) {
            $payload['report'] = $report;
        }

        Cache::put($cacheKey, $payload, now()->addHour());
    }

    /**
     * @param array<string, int> $beforeCounts
     * @param array<string, int> $afterCounts
     * @param array<string, int> $fetched
     * @param array<string, mixed> $resultPayload
     */
    private function storeHistory(
        string $city,
        bool $forceFallback,
        array $beforeCounts,
        array $afterCounts,
        array $fetched,
        array $resultPayload,
        $startedAt,
        $completedAt
    ): void {
        DirectorySyncHistory::create([
            'city' => $city,
            'status' => (string)($resultPayload['status'] ?? 'unknown'),
            'force_fallback' => $forceFallback,
            'fetched_hospitals' => (int)($fetched['hospitals'] ?? 0),
            'fetched_doctors' => (int)($fetched['doctors'] ?? 0),
            'fetched_blood_banks' => (int)($fetched['blood_banks'] ?? 0),
            'db_hospitals_before' => (int)($beforeCounts['hospitals'] ?? 0),
            'db_doctors_before' => (int)($beforeCounts['doctors'] ?? 0),
            'db_blood_banks_before' => (int)($beforeCounts['blood_banks'] ?? 0),
            'db_hospitals_after' => (int)($afterCounts['hospitals'] ?? 0),
            'db_doctors_after' => (int)($afterCounts['doctors'] ?? 0),
            'db_blood_banks_after' => (int)($afterCounts['blood_banks'] ?? 0),
            'delta_hospitals' => (int)($afterCounts['hospitals'] ?? 0) - (int)($beforeCounts['hospitals'] ?? 0),
            'delta_doctors' => (int)($afterCounts['doctors'] ?? 0) - (int)($beforeCounts['doctors'] ?? 0),
            'delta_blood_banks' => (int)($afterCounts['blood_banks'] ?? 0) - (int)($beforeCounts['blood_banks'] ?? 0),
            'message' => (string)($resultPayload['message'] ?? ''),
            'report_payload' => $resultPayload,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
        ]);
    }
}
