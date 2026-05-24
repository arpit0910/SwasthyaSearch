<?php

namespace App\Console\Commands;

use App\Services\GooglePlacesHospitalEnrichmentService;
use Illuminate\Console\Command;

class EnrichHospitalsFromGoogleCommand extends Command
{
    protected $signature = 'healthcare:enrich-hospitals-google 
                            {--city= : Optional city filter}
                            {--limit=200 : Max hospitals to enrich per run}';

    protected $description = 'Fill missing hospital details using Google Places API (official Google Business listing data).';

    public function handle(GooglePlacesHospitalEnrichmentService $service): int
    {
        $city = $this->option('city') ? (string)$this->option('city') : null;
        $limit = max(1, (int)$this->option('limit'));

        $this->info('Starting hospital enrichment from Google Places...');
        if ($city) {
            $this->line('City filter: ' . $city);
        }
        $this->line('Limit: ' . $limit);

        $report = $service->enrichMissingHospitals($city, $limit);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Processed', $report['processed'] ?? 0],
                ['Updated', $report['updated'] ?? 0],
                ['Failed', $report['failed'] ?? 0],
            ]
        );

        if (($report['processed'] ?? 0) === 0) {
            $this->warn('No hospitals matched missing-data criteria or Google API key is missing.');
        }

        return Command::SUCCESS;
    }
}

