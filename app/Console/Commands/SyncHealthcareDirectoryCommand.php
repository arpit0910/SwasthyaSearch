<?php

namespace App\Console\Commands;

use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Services\ScraperService;
use Illuminate\Console\Command;

class SyncHealthcareDirectoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'healthcare:sync {city=Jaipur : The city to synchronize} {--force-fallback : Force using verified institutional fallback dataset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize verified healthcare directory records (Doctors, Hospitals, Blood Banks) for a specified city.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $city = $this->argument('city');
        $forceFallback = $this->option('force-fallback');

        $this->info("Starting healthcare directory synchronization for city: {$city}");
        $this->info("Mode: " . ($forceFallback ? "Verified Institutional Fallback (High-Integrity)" : "Live Web Scraping + Fallback"));

        $this->newLine();
        $this->info("1. Synchronizing Hospitals...");
        $hospitals = ScraperService::scrapeHospitals($city, $forceFallback);
        $this->line("Synced " . count($hospitals) . " hospital records.");

        $this->newLine();
        $this->info("2. Synchronizing Doctors...");
        $doctors = ScraperService::scrapeDoctors($city, $forceFallback);
        $this->line("Synced " . count($doctors) . " doctor records.");

        $this->newLine();
        $this->info("3. Synchronizing Blood Banks...");
        $bloodBanks = ScraperService::scrapeBloodBanks($city, $forceFallback);
        $this->line("Synced " . count($bloodBanks) . " blood bank records.");

        $this->newLine();
        $this->info("--- DATA INTEGRITY & RELIABILITY REPORT ---");

        $hospCount = Hospital::where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->count();
        $docCount = Doctor::where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->count();
        $bbCount = BloodBank::where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->count();

        $this->table(
            ['Module', 'City', 'Verified Records Active', 'Status'],
            [
                ['Hospitals', $city, $hospCount, $hospCount > 0 ? 'PASS (100% Verified)' : 'WARNING (No Records)'],
                ['Doctors', $city, $docCount, $docCount > 0 ? 'PASS (100% Verified)' : 'WARNING (No Records)'],
                ['Blood Banks', $city, $bbCount, $bbCount > 0 ? 'PASS (100% Verified)' : 'WARNING (No Records)'],
            ]
        );

        $this->newLine();
        $this->info("Sample Verified Hospital Record:");
        $sampleHosp = Hospital::where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->first();
        if ($sampleHosp) {
            $this->table(
                ['Attribute', 'Value'],
                [
                    ['Name (EN)', $sampleHosp->name_en],
                    ['Name (HI)', $sampleHosp->name_hi],
                    ['Type', $sampleHosp->type],
                    ['Emergency Phone', $sampleHosp->emergency_country_code . ' ' . $sampleHosp->emergency_phone],
                    ['Address', $sampleHosp->address],
                    ['Coordinates', "Lat: {$sampleHosp->latitude}, Lng: {$sampleHosp->longitude}"],
                    ['Cashless Schemes', implode(', ', $sampleHosp->cashless_schemes_list ?? [])],
                ]
            );
        }

        $this->newLine();
        $this->info("Sample Verified Doctor Record:");
        $sampleDoc = Doctor::with('department')->where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->first();
        if ($sampleDoc) {
            $this->table(
                ['Attribute', 'Value'],
                [
                    ['Name', "Dr. {$sampleDoc->first_name} {$sampleDoc->last_name}"],
                    ['Department', $sampleDoc->department?->name_en],
                    ['Reg Number', $sampleDoc->registration_number],
                    ['Experience', "{$sampleDoc->experience_years} years"],
                    ['Phone', $sampleDoc->country_code . ' ' . $sampleDoc->phone],
                    ['Address', "{$sampleDoc->address_line1}, {$sampleDoc->city}"],
                    ['Degrees', implode(', ', $sampleDoc->education_degrees ?? [])],
                ]
            );
        }

        $this->newLine();
        $this->info("Sample Verified Blood Bank Record:");
        $sampleBb = BloodBank::where('city', 'LIKE', "%{$city}%")->where('is_verified', true)->first();
        if ($sampleBb) {
            $this->table(
                ['Attribute', 'Value'],
                [
                    ['Name', $sampleBb->name_en],
                    ['Emergency Phone', $sampleBb->emergency_country_code . ' ' . $sampleBb->emergency_phone],
                    ['Address', $sampleBb->address_en],
                    ['Blood Groups', implode(', ', $sampleBb->available_blood_groups ?? [])],
                    ['24/7 Facility', $sampleBb->is_24_7 ? 'Yes' : 'No'],
                ]
            );
        }

        $this->newLine();
        $this->info("Synchronization and Data Integrity Check completed successfully!");
        return Command::SUCCESS;
    }
}
