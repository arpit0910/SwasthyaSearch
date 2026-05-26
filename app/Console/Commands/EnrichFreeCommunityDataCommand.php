<?php

namespace App\Console\Commands;

use App\Services\FreeCommunityEnrichmentService;
use Illuminate\Console\Command;

class EnrichFreeCommunityDataCommand extends Command
{
    protected $signature = 'healthcare:enrich-free 
                            {--city= : Optional city filter}
                            {--module=all : all|hospitals|doctors|blood-banks}
                            {--limit=300 : Max records per module in one run}';

    protected $description = 'Free community enrichment using open data geocoding (OpenStreetMap/Nominatim).';

    public function handle(FreeCommunityEnrichmentService $service): int
    {
        $city = $this->option('city') ? trim((string)$this->option('city')) : null;
        $module = strtolower(trim((string)$this->option('module')));
        $limit = max(1, (int)$this->option('limit'));

        $this->info('Starting free community enrichment...');
        if (!empty($city)) {
            $this->line('City: ' . $city);
        }
        $this->line('Module: ' . $module);
        $this->line('Limit per module: ' . $limit);

        // Map modules to their respective service methods
        $report = match ($module) {
            'all' => $service->enrichAll($city, $limit),
            'hospitals' => $service->enrichHospitals($city, $limit),
            'doctors' => $service->enrichDoctors($city, $limit),
            'blood-banks' => $service->enrichBloodBanks($city, $limit),
            default => null,
        };

        if ($report === null) {
            $this->error('Invalid module. Use all|hospitals|doctors|blood-banks');
            return Command::FAILURE;
        }

        // Dynamically build the table rows from the returned report keys
        $tableRows = [];
        foreach ($report as $key => $value) {
            // Prettify keys: 'hospitals_processed' -> 'Hospitals Processed'
            $metricName = ucwords(str_replace('_', ' ', $key));
            $tableRows[] = [$metricName, $value];
        }

        $this->table(['Metric', 'Value'], $tableRows);
        $this->info('Enrichment completed successfully.');

        return Command::SUCCESS;
    }
}