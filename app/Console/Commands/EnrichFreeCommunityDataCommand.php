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

        if ($module === 'all') {
            $report = $service->enrichAll($city, $limit);
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Hospitals Processed', $report['hospitals_processed']],
                    ['Hospitals Updated', $report['hospitals_updated']],
                    ['Hospitals Failed', $report['hospitals_failed']],
                    ['Doctors Processed', $report['doctors_processed']],
                    ['Doctors Updated', $report['doctors_updated']],
                    ['Doctors Failed', $report['doctors_failed']],
                    ['Blood Banks Processed', $report['blood_banks_processed']],
                    ['Blood Banks Updated', $report['blood_banks_updated']],
                    ['Blood Banks Failed', $report['blood_banks_failed']],
                ]
            );
            return Command::SUCCESS;
        }

        if ($module === 'hospitals') {
            $r = $service->enrichHospitals($city, $limit);
        } elseif ($module === 'doctors') {
            $r = $service->enrichDoctors($city, $limit);
        } elseif ($module === 'blood-banks') {
            $r = $service->enrichBloodBanks($city, $limit);
        } else {
            $this->error('Invalid module. Use all|hospitals|doctors|blood-banks');
            return Command::FAILURE;
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Processed', $r['processed']],
                ['Updated', $r['updated']],
                ['Failed', $r['failed']],
            ]
        );

        return Command::SUCCESS;
    }
}

