<?php

namespace App\Console\Commands;

use App\Services\DirectoryDeduplicationService;
use Illuminate\Console\Command;

class DedupeDirectoryCommand extends Command
{
    protected $signature = 'healthcare:dedupe {city? : Optional city to dedupe only one city}';

    protected $description = 'Remove duplicate doctors, hospitals, and blood banks safely.';

    public function handle(DirectoryDeduplicationService $deduplicationService): int
    {
        $city = $this->argument('city');
        $results = $deduplicationService->dedupe($city ? trim((string) $city) : null);

        $this->info('Directory deduplication completed.');
        $this->table(['Metric', 'Removed'], [
            ['Doctors', $results['doctors_removed']],
            ['Hospitals', $results['hospitals_removed']],
            ['Blood Banks', $results['blood_banks_removed']],
        ]);

        return self::SUCCESS;
    }
}
