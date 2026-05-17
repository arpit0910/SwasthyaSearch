<?php

namespace App\Console\Commands;

use App\Services\ScraperService;
use Illuminate\Console\Command;

class ScrapeJaipurDoctors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:doctors-jaipur {--url= : Specific URL to scrape} {--force-fallback : Force using the rich backup dataset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape doctors list and hospital details for Jaipur city across all departments and update existing records continuously';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting comprehensive doctor and hospital scraping & updating for Jaipur city...');

        $forceFallback = $this->option('force-fallback');
        $customUrl = $this->option('url');

        $this->info('Synchronizing hospitals & clinics...');
        $hospitals = ScraperService::scrapeHospitals('Jaipur', $forceFallback, $customUrl);
        $this->info('Successfully synchronized ' . count($hospitals) . ' hospitals with rich address & cashless scheme metadata.');

        $this->info('Synchronizing doctors across all active departments...');
        $doctors = ScraperService::scrapeDoctors('Jaipur', $forceFallback, $customUrl);
        $this->info('Successfully synchronized ' . count($doctors) . ' doctors across all departments.');

        $this->newLine();
        $this->info('Successfully scraped, updated, and synchronized all Jaipur healthcare directory data!');
    }
}
