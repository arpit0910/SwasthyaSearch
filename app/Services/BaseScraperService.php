<?php

namespace App\Services;

use App\Models\BloodBank;
use App\Models\Department;
use App\Services\HealthcareSyncService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;
use Exception;

class BaseScraperService
{
    /**
     * Scrape and synchronize doctors for a given city.
     */
    public static function scrapeDoctors(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $citySlug = strtolower(trim($city));
        $citySlug = str_replace(' ', '-', $citySlug);
        $cityName = ucfirst(trim($city));

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 5, 'message' => "Initializing doctor scraping for {$cityName}..."], 300);
        }

        $scrapedDoctors = [];
        $totalUrlsAttempted = 0;
        $failedUrls = [];
        $profilePagesEnriched = 0;

        if (!$forceFallback) {
            if ($customUrl) {
                $urlsToScrape = [$customUrl];
            } else {
                $baseUrls = [
                    "https://www.practo.com/{$citySlug}/doctors",
                    "https://www.lybrate.com/{$citySlug}/doctors",
                    "https://www.bajajfinservhealth.in/doctors/{$citySlug}",
                    "https://www.apollo247.com/doctors/doctors-in-{$citySlug}",
                ];

                $activeDepartments = Department::where('is_active', true)->where('name_en', '!=', '')->get();

                foreach ($activeDepartments as $dept) {
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($dept->name_en)));
                    $slug = trim($slug, '-');
                    if (!empty($slug)) {
                        $baseUrls[] = "https://www.practo.com/{$citySlug}/{$slug}";
                        $baseUrls[] = "https://www.practo.com/{$citySlug}/doctors/{$slug}";
                        $baseUrls[] = "https://www.lybrate.com/{$citySlug}/{$slug}";
                        $baseUrls[] = "https://www.bajajfinservhealth.in/doctors/{$citySlug}/{$slug}";
                    }
                }
                $baseUrls = array_values(array_unique($baseUrls));

                $urlsToScrape = [];
                foreach ($baseUrls as $baseUrl) {
                    $urlsToScrape[] = $baseUrl;
                }
            }

            $totalUrls = count($urlsToScrape);
            foreach ($urlsToScrape as $index => $baseUrl) {
                if ($cacheKey && $index % 5 === 0) {
                    $prog = 5 + (int)(($index / ($totalUrls ?: 1)) * 45);
                    Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => $prog, 'message' => "Fetching live data from department " . ($index + 1) . " of {$totalUrls}..."], 300);
                }

                $parts = explode('/', rtrim(explode('?', $baseUrl)[0], '/'));
                $lastPart = end($parts);
                $expectedDept = ucfirst(str_replace('-', ' ', $lastPart));
                if (strtolower($expectedDept) === 'doctors' || str_starts_with(strtolower($expectedDept), 'doctors-in') || strtolower($expectedDept) === $citySlug) {
                    $expectedDept = 'General Medicine';
                }

                // Paginate up to 25 pages per base URL, but stop if empty or duplicate-heavy
                for ($p = 1; $p <= 25; $p++) {
                    $url = $p === 1 ? $baseUrl : "{$baseUrl}?page={$p}";
                    $totalUrlsAttempted++;

                    try {
                        $parsed = Cache::remember('practo_doc_parsed_' . md5($url), 1800, function () use ($url, $cityName, $expectedDept) {
                            usleep(random_int(100000, 250000));
                            $response = Http::withoutVerifying()->retry(3, function ($attempt) {
                                return $attempt * 1000;
                            })->withHeaders([
                                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                                'Accept-Language' => 'en-US,en;q=0.5',
                                'Referer' => 'https://www.google.com/',
                            ])->timeout(10)->get($url);

                            if ($response->successful()) {
                                $html = $response->body();
                                $res = self::parseDoctorHtml($html, $cityName, $expectedDept);
                                unset($html);
                                return $res;
                            }
                            return [];
                        });

                        if (empty($parsed)) {
                            $failedUrls[] = $url;
                            break; // Stop paginating if no doctor cards found on this page
                        }

                        $scrapedDoctors = array_merge($scrapedDoctors, $parsed);
                    } catch (Exception $e) {
                        Log::warning("Error scraping {$url}: " . $e->getMessage());
                        $failedUrls[] = $url;
                        break;
                    }
                }
            }

            // Enrich profile pages
            if ($cacheKey) {
                Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 50, 'message' => "Enriching doctor profiles with direct mobile/clinic telephone numbers & exact credentials..."], 300);
            }

            foreach ($scrapedDoctors as &$doc) {
                if (!empty($doc['profile_url'])) {
                    $doc = self::enrichDoctorProfile($doc, $cityName);
                    $profilePagesEnriched++;
                }
            }
            unset($doc);
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        }

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 60, 'message' => "Enriching directory with comprehensive verified dataset & official hospital registries for {$cityName}..."], 300);
        }
        $fallbackDoctors = static::getFallbackDoctors($cityName);
        $officialDoctors = self::scrapeOfficialHospitalSources($cityName);
        $scrapedDoctors = array_merge($fallbackDoctors, $officialDoctors, $scrapedDoctors);

        // Deduplication & Merging
        $uniqueDoctors = [];
        $duplicatesMerged = 0;
        foreach ($scrapedDoctors as $doc) {
            $key = !empty($doc['profile_url']) ? strtolower(trim($doc['profile_url'])) : strtolower(trim(($doc['first_name'] ?? '') . ' ' . ($doc['last_name'] ?? '') . ' ' . ($doc['department_name_en'] ?? '') . ' ' . ($doc['hospital_name_en'] ?? '')));
            if (!isset($uniqueDoctors[$key])) {
                $uniqueDoctors[$key] = $doc;
            } else {
                $duplicatesMerged++;
                foreach ($doc as $field => $val) {
                    if (empty($uniqueDoctors[$key][$field]) && !empty($val)) {
                        $uniqueDoctors[$key][$field] = $val;
                    }
                }
            }
        }
        $scrapedDoctors = array_values($uniqueDoctors);
        [$scrapedDoctors, $doctorRejected] = self::filterDoctorRecords($scrapedDoctors, $cityName);
        if ($doctorRejected > 0) {
            Log::info("Filtered {$doctorRejected} low-confidence doctor records for {$cityName}.");
        }

        if (!empty($scrapedDoctors)) {
            HealthcareSyncService::syncBatch($scrapedDoctors, [], $cacheKey, 25, $cityName);
        } elseif ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'completed', 'city' => $cityName, 'progress' => 100, 'message' => "No reliable verified records found for {$cityName}."], 300);
        }

        return $scrapedDoctors;
    }

    public static function scrapeOfficialHospitalSources(string $cityName): array
    {
        $citySlug = strtolower(trim($cityName));
        $hospitals = [];

        if ($citySlug === 'delhi' || $citySlug === 'new-delhi') {
            $hospitals = [
                ['name' => 'All India Institute of Medical Sciences (AIIMS)', 'url' => 'https://aiims.edu/en/faculty-staff.html', 'type' => 'Government Hospital'],
                ['name' => 'Sir Ganga Ram Hospital', 'url' => 'https://sgrh.com/doctors', 'type' => 'Semi-Private Hospital'],
                ['name' => 'Max Super Speciality Hospital (Saket)', 'url' => 'https://maxhealthcare.in/doctors/delhi', 'type' => 'Private Hospital'],
                ['name' => 'Fortis Escorts Heart Institute', 'url' => 'https://fortishealthcare.com/doctors/delhi', 'type' => 'Private Hospital'],
                ['name' => 'Indraprastha Apollo Hospital', 'url' => 'https://apollohospitals.com/delhi/doctors', 'type' => 'Private Hospital'],
                ['name' => 'BLK-Max Super Speciality Hospital', 'url' => 'https://blkmaxhospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Rajiv Gandhi Cancer Institute', 'url' => 'https://rgcirc.org/doctors', 'type' => 'Semi-Private Hospital'],
                ['name' => 'St. Stephen\'s Hospital', 'url' => 'https://ststephenshospital.org/doctors', 'type' => 'Semi-Private Hospital'],
            ];
        } elseif ($citySlug === 'mumbai') {
            $hospitals = [
                ['name' => 'Tata Memorial Hospital', 'url' => 'https://tmc.gov.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'KEM Hospital', 'url' => 'https://kem.edu/doctors', 'type' => 'Government Hospital'],
                ['name' => 'P. D. Hinduja Hospital', 'url' => 'https://hindujahospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Lilavati Hospital & Research Centre', 'url' => 'https://lilavatihospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Kokilaben Dhirubhai Ambani Hospital', 'url' => 'https://kokilabenhospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Breach Candy Hospital', 'url' => 'https://breachcandyhospital.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Nanavati Max Super Speciality Hospital', 'url' => 'https://nanavatimaxhospital.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Fortis Hospital (Mulund)', 'url' => 'https://fortishealthcare.com/doctors/mumbai', 'type' => 'Private Hospital'],
                ['name' => 'Dr. L H Hiranandani Hospital', 'url' => 'https://hiranandanihospital.org/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'jodhpur') {
            $hospitals = [
                ['name' => 'All India Institute of Medical Sciences (AIIMS Jodhpur)', 'url' => 'https://aiimsjodhpur.edu.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Mathura Das Mathur (MDM) Hospital', 'url' => 'https://snmcjodhpur.ac.in/mdm/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Mahatma Gandhi Hospital (Jodhpur)', 'url' => 'https://snmcjodhpur.ac.in/mgh/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Medipulse Hospital', 'url' => 'https://medipulse.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Goyal Hospital & Research Centre', 'url' => 'https://goyalhospital.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Vasundhara Hospital', 'url' => 'https://vasundharahospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'ASG Eye Hospital', 'url' => 'https://asgeyehospital.com/doctors/jodhpur', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'kota') {
            $hospitals = [
                ['name' => 'Government Medical College (GMC) & MBS Hospital', 'url' => 'https://gmckota.ac.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Jay Kay Lon Hospital', 'url' => 'https://gmckota.ac.in/jkl/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Sudha Hospital & Medical Research Centre', 'url' => 'https://sudhahospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Maitri Hospital', 'url' => 'https://maitrihospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Jai Hospital', 'url' => 'https://jaihospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Kota Heart Institute', 'url' => 'https://kotaheart.com/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'jaipur') {
            $hospitals = [
                ['name' => 'Sawai Man Singh (SMS) Hospital', 'url' => 'https://education.rajasthan.gov.in/smsmcjaipur/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Santokba Durlabhji Memorial Hospital (SDMH)', 'url' => 'https://sdmh.in/doctors', 'type' => 'Semi-Private Hospital'],
                ['name' => 'Fortis Escorts Hospital', 'url' => 'https://fortishealthcare.com/doctors/jaipur', 'type' => 'Private Hospital'],
                ['name' => 'Manipal Hospital (Vidhyadhar Nagar)', 'url' => 'https://manipalhospitals.com/jaipur/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Narayana Multispeciality Hospital', 'url' => 'https://narayanahealth.org/hospitals/jaipur/doctors', 'type' => 'Private Hospital'],
                ['name' => 'CK Birla Hospital (RBH)', 'url' => 'https://rbh.in/find-a-doctor', 'type' => 'Private Hospital'],
                ['name' => 'Apex Hospital', 'url' => 'https://apexhospitals.com/doctors/jaipur', 'type' => 'Private Hospital'],
                ['name' => 'Mahatma Gandhi Hospital', 'url' => 'https://mgumst.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Bhagwan Mahaveer Cancer Hospital', 'url' => 'https://bmchrc.org/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'pune') {
            $hospitals = [
                ['name' => 'Sassoon General Hospital', 'url' => 'https://bjmcpune.org/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Ruby Hall Clinic', 'url' => 'https://rubyhall.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Jehangir Hospital', 'url' => 'https://jehangirhospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Deenanath Mangeshkar Hospital', 'url' => 'https://dmhospital.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Sahyadri Super Speciality Hospital', 'url' => 'https://sahyadrihospital.com/doctors/pune', 'type' => 'Private Hospital'],
                ['name' => 'Aditya Birla Memorial Hospital', 'url' => 'https://adityabirlahospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'KEM Hospital Pune', 'url' => 'https://kempune.org/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'bangalore' || $citySlug === 'bengaluru') {
            $hospitals = [
                ['name' => 'NIMHANS', 'url' => 'https://nimhans.ac.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Victoria Hospital (BMCRI)', 'url' => 'https://bmcri.org/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Manipal Hospital (Old Airport Road)', 'url' => 'https://manipalhospitals.com/bangalore/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Fortis Hospital (Bannerghatta)', 'url' => 'https://fortishealthcare.com/doctors/bangalore', 'type' => 'Private Hospital'],
                ['name' => 'Apollo Hospitals (Bannerghatta)', 'url' => 'https://apollohospitals.com/bangalore/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Narayana Health City (Bommasandra)', 'url' => 'https://narayanahealth.org/hospitals/bangalore/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Aster CMI Hospital', 'url' => 'https://asterhospitals.in/doctors/bangalore', 'type' => 'Private Hospital'],
                ['name' => 'Sakra World Hospital', 'url' => 'https://sakraworldhospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'St. John\'s Medical College Hospital', 'url' => 'https://stjohns.in/doctors', 'type' => 'Semi-Private Hospital'],
            ];
        } elseif ($citySlug === 'hyderabad') {
            $hospitals = [
                ['name' => 'Osmania General Hospital', 'url' => 'https://osmaniageneralhospital.org/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Gandhi Hospital', 'url' => 'https://gandhihospital.org/doctors', 'type' => 'Government Hospital'],
                ['name' => 'NIMS', 'url' => 'https://nims.edu.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Apollo Hospitals (Jubilee Hills)', 'url' => 'https://apollohospitals.com/hyderabad/doctors', 'type' => 'Private Hospital'],
                ['name' => 'AIG Hospitals (Gachibowli)', 'url' => 'https://aighospitals.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Yashoda Hospitals (Secunderabad)', 'url' => 'https://yashodahospitals.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'CARE Hospitals (Banjara Hills)', 'url' => 'https://carehospitals.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'KIMS Hospitals (Secunderabad)', 'url' => 'https://kimshospitals.com/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'ahmedabad') {
            $hospitals = [
                ['name' => 'Civil Hospital Ahmedabad', 'url' => 'https://bjmcabd.edu.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'SVP Hospital', 'url' => 'https://svphospital.com/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Apollo Hospitals (Bhat)', 'url' => 'https://apollohospitals.com/ahmedabad/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Zydus Hospital', 'url' => 'https://zydushospitals.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'KD Hospital', 'url' => 'https://kdhospital.co.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Sterling Hospital', 'url' => 'https://sterlinghospitals.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'CIMS Hospital', 'url' => 'https://cims.org/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Shalby Hospital (S.G. Road)', 'url' => 'https://shalby.org/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'kolkata' || $citySlug === 'calcutta') {
            $hospitals = [
                ['name' => 'SSKM Hospital (IPGMER)', 'url' => 'https://ipgmer.gov.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Calcutta Medical College', 'url' => 'https://medicalcollegekolkata.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'AMRI Hospitals (Dhakuria)', 'url' => 'https://amrihospitals.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Apollo Multispeciality Hospitals (Salt Lake)', 'url' => 'https://apollohospitals.com/kolkata/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Fortis Hospital (Anandapur)', 'url' => 'https://fortishealthcare.com/doctors/kolkata', 'type' => 'Private Hospital'],
                ['name' => 'Peerless Hospital', 'url' => 'https://peerlesshospital.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Medica Superspecialty Hospital', 'url' => 'https://medicahospitals.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'RN Tagore Hospital (RTIICS)', 'url' => 'https://narayanahealth.org/hospitals/kolkata/doctors', 'type' => 'Private Hospital'],
            ];
        } elseif ($citySlug === 'chennai' || $citySlug === 'madras') {
            $hospitals = [
                ['name' => 'Rajiv Gandhi Government General Hospital (RGGGH)', 'url' => 'https://rgggh.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Stanley Medical College Hospital', 'url' => 'https://stanleymedicalcollege.in/doctors', 'type' => 'Government Hospital'],
                ['name' => 'Apollo Hospitals (Greams Road)', 'url' => 'https://apollohospitals.com/chennai/doctors', 'type' => 'Private Hospital'],
                ['name' => 'MGM Healthcare', 'url' => 'https://mgmhealthcare.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Kauvery Hospital (Alwarpet)', 'url' => 'https://kauveryhospital.com/doctors/chennai', 'type' => 'Private Hospital'],
                ['name' => 'MIOT International', 'url' => 'https://miotinternational.com/doctors', 'type' => 'Private Hospital'],
                ['name' => 'Sri Ramachandra Medical Centre (SRMC)', 'url' => 'https://sriramachandra.edu.in/doctors', 'type' => 'Private Hospital'],
                ['name' => 'SIMS Hospital (Vadapalani)', 'url' => 'https://simshospitals.com/doctors', 'type' => 'Private Hospital'],
            ];
        }

        $doctors = [];
        foreach ($hospitals as $hosp) {
            try {
                $url = $hosp['url'];
                $hospDoctors = Cache::remember('official_hosp_parsed_' . md5($url), 3600, function () use ($url, $hosp, $cityName) {
                    usleep(random_int(100000, 200000));
                    $response = Http::withoutVerifying()->retry(3, function ($attempt) {
                        return $attempt * 1000;
                    })->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    ])->timeout(8)->get($url);

                    if (!$response->successful()) {
                        return [];
                    }

                    $html = $response->body();
                    $parsedDocs = [];

                    libxml_use_internal_errors(true);
                    $dom = new DOMDocument();
                    $dom->loadHTML($html);
                    $xpath = new DOMXPath($dom);

                    $cards = $xpath->query("//div[contains(@class, 'doctor') or contains(@class, 'doc-card') or contains(@class, 'team') or contains(@class, 'card')]");
                    foreach ($cards as $card) {
                        $doc = [
                            'hospital_name_en' => $hosp['name'],
                            'city' => $cityName,
                            'source_name' => "Official Registry ({$hosp['name']})",
                            'source_url' => $url,
                            'source_confidence_score' => 98,
                            'source_verification_status' => 'verified_active',
                            'is_verified' => true,
                        ];

                        $nameNode = $xpath->query(".//h3 | .//h4 | .//div[contains(@class, 'name')]", $card)->item(0);
                        if ($nameNode) {
                            $fullName = trim(str_replace(['Dr.', 'Dr '], '', $nameNode->textContent));
                            $parts = explode(' ', $fullName, 2);
                            $doc['first_name'] = trim($parts[0]);
                            $doc['last_name'] = trim($parts[1] ?? '');
                        }

                        $deptNode = $xpath->query(".//div[contains(@class, 'dept') or contains(@class, 'special')] | .//span[contains(@class, 'special')]", $card)->item(0);
                        if ($deptNode) {
                            $doc['department_name_en'] = trim($deptNode->textContent);
                        }

                        $qualNode = $xpath->query(".//div[contains(@class, 'qual') or contains(@class, 'degree')]", $card)->item(0);
                        if ($qualNode) {
                            $doc['education_degrees'] = array_map('trim', explode(',', trim($qualNode->textContent)));
                        }

                        if (!empty($doc['first_name'])) {
                            $parsedDocs[] = $doc;
                        }
                    }

                    unset($html, $dom, $xpath);
                    libxml_clear_errors();
                    return $parsedDocs;
                });

                if (!empty($hospDoctors)) {
                    $doctors = array_merge($doctors, $hospDoctors);
                }
            } catch (Exception $e) {
                Log::warning("Error scraping official hospital {$hosp['url']}: " . $e->getMessage());
            }
        }

        return $doctors;
    }
    /**
     * Scrape and synchronize hospitals for a given city.
     */
    public static function scrapeHospitals(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $citySlug = strtolower(trim($city));
        $citySlug = str_replace(' ', '-', $citySlug);
        $cityName = ucfirst(trim($city));

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 10, 'message' => "Initializing hospital scraping for {$cityName}..."], 300);
        }

        $scrapedHospitals = [];

        if (!$forceFallback) {
            $urlsToScrape = $customUrl ? [$customUrl] : [
                "https://www.practo.com/{$citySlug}/hospitals",
                "https://www.practo.com/{$citySlug}/hospitals?page=2",
                "https://www.practo.com/{$citySlug}/hospitals?page=3",
                "https://www.practo.com/{$citySlug}/hospitals?page=4",
                "https://www.practo.com/{$citySlug}/hospitals?page=5",
                "https://www.practo.com/{$citySlug}/hospitals?page=6",
                "https://www.practo.com/{$citySlug}/hospitals?page=7",
                "https://www.practo.com/{$citySlug}/hospitals?page=8",
                "https://www.practo.com/{$citySlug}/hospitals?page=9",
                "https://www.practo.com/{$citySlug}/hospitals?page=10",
                "https://www.practo.com/{$citySlug}/clinics",
                "https://www.practo.com/{$citySlug}/clinics?page=2",
                "https://www.practo.com/{$citySlug}/clinics?page=3",
                "https://www.practo.com/{$citySlug}/clinics?page=4",
                "https://www.practo.com/{$citySlug}/clinics?page=5",
                "https://www.practo.com/{$citySlug}/clinics?page=6",
                "https://www.practo.com/{$citySlug}/clinics?page=7",
                "https://www.practo.com/{$citySlug}/clinics?page=8",
                "https://www.practo.com/{$citySlug}/clinics?page=9",
                "https://www.practo.com/{$citySlug}/clinics?page=10",
            ];

            $totalUrls = count($urlsToScrape);
            foreach ($urlsToScrape as $index => $url) {
                if ($cacheKey) {
                    $prog = 10 + (int)(($index / $totalUrls) * 40);
                    Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => $prog, 'message' => "Fetching live hospital directory..."], 300);
                }

                try {
                    $response = Http::withoutVerifying()->retry(3, function ($attempt) {
                        return $attempt * 1000;
                    })->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'en-US,en;q=0.5',
                        'Referer' => 'https://www.google.com/',
                    ])->timeout(10)->get($url);

                    if ($response->successful()) {
                        $html = $response->body();
                        $parsed = self::parseHospitalHtml($html, $cityName);
                        if (!empty($parsed)) {
                            $scrapedHospitals = array_merge($scrapedHospitals, $parsed);
                        }
                    }
                } catch (Exception $e) {
                    Log::warning("Error scraping hospitals {$url}: " . $e->getMessage());
                }
            }
        }

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 60, 'message' => "Enriching directory with comprehensive verified hospital dataset for {$cityName}..."], 300);
        }
        $fallbackHospitals = static::getFallbackHospitals($cityName);
        $scrapedHospitals = array_merge($fallbackHospitals, $scrapedHospitals);

        $uniqueHospitals = [];
        foreach ($scrapedHospitals as $hosp) {
            $key = strtolower(trim($hosp['name_en']));
            if (!isset($uniqueHospitals[$key])) {
                $uniqueHospitals[$key] = $hosp;
            }
        }
        $scrapedHospitals = array_values($uniqueHospitals);
        [$scrapedHospitals, $hospitalRejected] = self::filterHospitalRecords($scrapedHospitals, $cityName);
        if ($hospitalRejected > 0) {
            Log::info("Filtered {$hospitalRejected} low-confidence hospital records for {$cityName}.");
        }

        if (!empty($scrapedHospitals)) {
            HealthcareSyncService::syncBatch([], $scrapedHospitals, $cacheKey, 25, $cityName);
        } elseif ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'completed', 'city' => $cityName, 'progress' => 100, 'message' => "No reliable verified hospital records found for {$cityName}."], 300);
        }

        return $scrapedHospitals;
    }

    /**
     * Scrape and synchronize blood banks for a given city.
     */
    public static function scrapeBloodBanks(string $city, bool $forceFallback = false, ?string $customUrl = null, ?string $cacheKey = null): array
    {
        $cityName = ucfirst(trim($city));

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'running', 'city' => $cityName, 'progress' => 10, 'message' => "Initializing blood bank scraping for {$cityName}..."], 300);
        }

        $scrapedBloodBanks = static::getFallbackBloodBanks($cityName);
        [$scrapedBloodBanks, $bloodBankRejected] = self::filterBloodBankRecords($scrapedBloodBanks, $cityName);
        if ($bloodBankRejected > 0) {
            Log::info("Filtered {$bloodBankRejected} low-confidence blood bank records for {$cityName}.");
        }

        foreach ($scrapedBloodBanks as $bb) {
            BloodBank::updateOrCreate(
                ['name_en' => $bb['name_en'], 'city' => $bb['city']],
                $bb
            );
        }

        if ($cacheKey) {
            Cache::put($cacheKey, ['status' => 'completed', 'city' => $cityName, 'progress' => 100, 'message' => empty($scrapedBloodBanks) ? "No reliable verified blood bank records found for {$cityName}." : "Successfully synchronized " . count($scrapedBloodBanks) . " verified blood banks for {$cityName}!"], 300);
        }

        return $scrapedBloodBanks;
    }

    protected static function getFallbackDoctors(string $cityName): array
    {
        return [];
    }

    protected static function getFallbackHospitals(string $cityName): array
    {
        return [];
    }

    protected static function getFallbackBloodBanks(string $cityName): array
    {
        return [];
    }

    protected static function parseDoctorHtml(string $html, string $cityName, string $expectedDept = 'General Medicine'): array
    {
        $doctors = [];
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $cards = $xpath->query("//div[contains(@class, 'listing-doctor-card') or contains(@class, 'info-section') or contains(@data-qa-id, 'doctor_card')]");

        foreach ($cards as $card) {
            $doc = [];
            $nameNode = $xpath->query(".//h2 | .//div[contains(@class, 'doctor-name')] | .//a[contains(@class, 'pr-doctor-name')]", $card)->item(0);
            if ($nameNode) {
                $fullName = trim(str_replace(['Dr.', 'Dr '], '', $nameNode->textContent));
                $parts = explode(' ', $fullName, 2);
                $doc['first_name'] = trim($parts[0]);
                $doc['last_name'] = trim($parts[1] ?? '');
            }

            $linkNode = $xpath->query(".//a[contains(@href, '/doctor/')]", $card)->item(0);
            if ($linkNode) {
                $href = trim($linkNode->getAttribute('href'));
                $doc['profile_url'] = str_starts_with($href, 'http') ? $href : "https://www.practo.com" . ($href[0] === '/' ? '' : '/') . $href;
            }

            $ratingNode = $xpath->query(".//span[contains(@class, 'star-rating') or contains(@data-qa-id, 'doctor_rating')]", $card)->item(0);
            if ($ratingNode) {
                $doc['rating'] = trim($ratingNode->textContent);
            }
            $recNode = $xpath->query(".//span[contains(@class, 'recommend') or contains(@data-qa-id, 'doctor_recommendation')]", $card)->item(0);
            if ($recNode) {
                $doc['recommendation_percent'] = trim($recNode->textContent);
            }
            $availNode = $xpath->query(".//div[contains(@class, 'availability') or contains(@data-qa-id, 'doctor_availability')]", $card)->item(0);
            if ($availNode) {
                $doc['availability'] = trim($availNode->textContent);
            }

            $specialtyNode = $xpath->query(".//div[contains(@class, 'u-d-flex')]/span | .//div[contains(@class, 'pr-doctor-speciality')] | .//div[contains(@class, 'speciality')]", $card)->item(0);
            if ($specialtyNode) {
                $doc['department_name_en'] = trim($specialtyNode->textContent);
            } else {
                $doc['department_name_en'] = $expectedDept;
            }

            $hospitalNode = $xpath->query(".//div[contains(@class, 'pr-hospital-name')] | .//span[contains(@class, 'clinic-name')] | .//a[contains(@href, '/hospital/')]", $card)->item(0);
            if ($hospitalNode) {
                $doc['hospital_name_en'] = trim($hospitalNode->textContent);
            }

            $localityNode = $xpath->query(".//div[contains(@class, 'pr-locality')] | .//span[contains(@class, 'locality')]", $card)->item(0);
            if ($localityNode) {
                $doc['address'] = trim($localityNode->textContent) . ", {$cityName}";
            }
            $doc['city'] = $cityName;

            $expNode = $xpath->query(".//div[contains(@class, 'pr-experience')] | .//div[contains(@data-qa-id, 'doctor_experience')]", $card)->item(0);
            if ($expNode) {
                if (preg_match('/(\d+)\s*\+?\s*years?/i', $expNode->textContent, $matches)) {
                    $doc['experience_years'] = (int)$matches[1];
                }
            }

            $feeNode = $xpath->query(".//div[contains(@data-qa-id, 'consultation_fee')] | .//span[contains(@class, 'pr-consultation-fee')]", $card)->item(0);
            if ($feeNode) {
                if (preg_match('/₹?\s*(\d+)/', $feeNode->textContent, $matches)) {
                    $doc['consultation_fee'] = (float)$matches[1];
                }
            }

            $qualNode = $xpath->query(".//div[contains(@class, 'pr-doctor-qualifications')] | .//div[contains(@class, 'qualification')] | .//div[contains(@data-qa-id, 'doctor_qualifications')] | .//span[contains(@class, 'doctor-qualifications')]", $card)->item(0);
            if ($qualNode) {
                $degreesStr = trim($qualNode->textContent);
                $doc['education_degrees'] = array_map('trim', explode(',', $degreesStr));
            }

            if (!empty($doc['first_name'])) {
                $doctors[] = $doc;
            }
        }

        libxml_clear_errors();
        return $doctors;
    }

    protected static function enrichDoctorProfile(array $doc, string $cityName): array
    {
        if (empty($doc['profile_url'])) {
            return $doc;
        }

        try {
            $url = $doc['profile_url'];
            $enrichedFields = Cache::remember('practo_profile_fields_' . md5($url), 3600, function () use ($url) {
                usleep(random_int(100000, 250000)); // Throttling / jitter
                $response = Http::withoutVerifying()->retry(3, function ($attempt) {
                    return $attempt * 1000;
                })->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                ])->timeout(10)->get($url);

                if (!$response->successful()) {
                    return [];
                }

                $html = $response->body();
                $fields = [];

                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $xpath = new DOMXPath($dom);

                // Extract Registration Number
                $regNode = $xpath->query("//div[contains(@class, 'registration-details') or contains(@data-qa-id, 'doctor_registration')] | //span[contains(text(), 'Registration')]/following-sibling::span")->item(0);
                if ($regNode) {
                    $regText = trim($regNode->textContent);
                    if (preg_match('/([A-Z0-9\-\/]+)/i', $regText, $m)) {
                        $fields['registration_number'] = $m[1];
                    }
                }

                // Extract Medical Council
                $councilNode = $xpath->query("//span[contains(text(), 'Medical Council')]/following-sibling::span | //div[contains(@class, 'council-name')]")->item(0);
                if ($councilNode) {
                    $fields['medical_council'] = trim($councilNode->textContent);
                }

                // Extract Memberships
                $memNodes = $xpath->query("//div[contains(@class, 'memberships') or contains(@data-qa-id, 'doctor_memberships')]//li");
                if ($memNodes->length > 0) {
                    $memberships = [];
                    foreach ($memNodes as $node) {
                        $memberships[] = trim($node->textContent);
                    }
                    $fields['membership_fellowships'] = implode(', ', $memberships);
                }

                // Extract Services
                $srvNodes = $xpath->query("//div[contains(@class, 'services') or contains(@data-qa-id, 'doctor_services')]//div[contains(@class, 'service-name')]");
                if ($srvNodes->length > 0) {
                    $services = [];
                    foreach ($srvNodes as $node) {
                        $services[] = trim($node->textContent);
                    }
                    $fields['specialization_summary'] = implode(', ', $services);
                }

                // Extract Timings
                $timeNode = $xpath->query("//div[contains(@class, 'timings') or contains(@data-qa-id, 'clinic_timings')]")->item(0);
                if ($timeNode) {
                    $fields['timings'] = trim($timeNode->textContent);
                }

                // Extract Gender from bio/title
                $bioNode = $xpath->query("//div[contains(@class, 'summary') or contains(@data-qa-id, 'doctor_summary')]")->item(0);
                $bio = $bioNode ? strtolower($bioNode->textContent) : '';
                if (str_contains($bio, ' she ') || str_contains($bio, ' her ')) {
                    $fields['gender'] = 'Female';
                } elseif (str_contains($bio, ' he ') || str_contains($bio, ' his ')) {
                    $fields['gender'] = 'Male';
                }

                // Extract embedded JSON-LD for exact coordinates & phone
                if (preg_match('/<script type="application\/ld\+json">([\s\S]*?)<\/script>/i', $html, $matches)) {
                    $jsonLd = json_decode($matches[1], true);
                    if (is_array($jsonLd)) {
                        array_walk_recursive($jsonLd, function ($item, $key) use (&$fields) {
                            if ($key === 'telephone' && empty($fields['phone'])) {
                                $fields['phone'] = trim($item);
                            }
                            if ($key === 'latitude' && empty($fields['latitude'])) {
                                $fields['latitude'] = (float)$item;
                            }
                            if ($key === 'longitude' && empty($fields['longitude'])) {
                                $fields['longitude'] = (float)$item;
                            }
                            if ($key === 'gender' && empty($fields['gender'])) {
                                $fields['gender'] = ucfirst(strtolower(trim($item)));
                            }
                        });
                    }
                }

                unset($html, $dom, $xpath);
                libxml_clear_errors();
                return $fields;
            });

            if (!empty($enrichedFields)) {
                $doc = array_merge($doc, $enrichedFields);
            }
        } catch (Exception $e) {
            Log::warning("Error enriching profile {$doc['profile_url']}: " . $e->getMessage());
        }

        return $doc;
    }

    protected static function parseHospitalHtml(string $html, string $cityName): array
    {
        $hospitals = [];
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $cards = $xpath->query("//div[contains(@class, 'c-card') or contains(@class, 'c-estb-card') or contains(@data-qa-id, 'hospital_card')]");

        foreach ($cards as $card) {
            $hosp = [];
            $nameNode = $xpath->query(".//h2 | .//a[contains(@class, 'c-estb-card__name')] | .//div[contains(@class, 'hospital-name')]", $card)->item(0);
            if ($nameNode) {
                $hosp['name_en'] = trim($nameNode->textContent);
            }

            $localityNode = $xpath->query(".//span[contains(@class, 'c-estb-card__locality')] | .//div[contains(@class, 'hospital-address')]", $card)->item(0);
            if ($localityNode) {
                $hosp['address'] = trim($localityNode->textContent) . ", {$cityName}";
            }

            $nameEnLower = strtolower($hosp['name_en'] ?? '');
            if (str_contains($nameEnLower, 'govt') || str_contains($nameEnLower, 'government') || str_contains($nameEnLower, 'aiims') || str_contains($nameEnLower, 'district hospital') || str_contains($nameEnLower, 'civil hospital') || str_contains($nameEnLower, 'sawai man singh') || str_contains($nameEnLower, 'mahatma gandhi') || str_contains($nameEnLower, 'esi')) {
                $hosp['type'] = 'Government Hospital';
            } elseif (str_contains($nameEnLower, 'trust') || str_contains($nameEnLower, 'foundation') || str_contains($nameEnLower, 'mission') || str_contains($nameEnLower, 'charitable') || str_contains($nameEnLower, 'society') || str_contains($nameEnLower, 'memorial')) {
                $hosp['type'] = 'Semi-Private Hospital';
            } elseif (str_contains($nameEnLower, 'clinic') || str_contains($nameEnLower, 'poly clinic') || str_contains($nameEnLower, 'dental') || str_contains($nameEnLower, 'care centre')) {
                $hosp['type'] = 'Clinic';
            } else {
                $hosp['type'] = 'Private Hospital';
            }

            if (!empty($hosp['name_en'])) {
                $hospitals[] = $hosp;
            }
        }

        libxml_clear_errors();
        return $hospitals;
    }

    public static function getRealDegreesForDepartment(string $deptEn): array
    {
        $map = [
            'General Physician' => ['MBBS', 'MD (General Medicine)', 'DNB (General Medicine)'],
            'Pediatrics' => ['MBBS', 'MD (Pediatrics)', 'DNB (Pediatrics)', 'Diploma in Child Health (DCH)'],
            'Cardiology' => ['MBBS', 'MD (Medicine)', 'DM (Cardiology)', 'DNB (Cardiology)', 'FACC'],
            'Orthopedics' => ['MBBS', 'MS (Orthopedics)', 'DNB (Orthopedics)', 'Diploma in Orthopedics'],
            'Gynecology' => ['MBBS', 'MD (Obstetrics & Gynecology)', 'MS (OBG)', 'DGO', 'DNB (OBG)'],
            'Dermatology' => ['MBBS', 'MD (Dermatology)', 'DDVL', 'DNB (Dermatology)'],
            'Ophthalmology' => ['MBBS', 'MS (Ophthalmology)', 'DO', 'DNB (Ophthalmology)'],
            'Dentistry' => ['BDS', 'MDS (Oral & Maxillofacial Surgery)', 'MDS (Orthodontics)'],
            'ENT' => ['MBBS', 'MS (ENT)', 'DLO', 'DNB (ENT)'],
            'Psychiatry' => ['MBBS', 'MD (Psychiatry)', 'DPM', 'DNB (Psychiatry)'],
            'Neurology' => ['MBBS', 'MD (Medicine)', 'DM (Neurology)', 'DNB (Neurology)'],
            'General Surgery' => ['MBBS', 'MS (General Surgery)', 'DNB (General Surgery)', 'FRCS'],
            'Ayurveda' => ['BAMS', 'MD (Ayurveda)', 'MS (Ayurveda)'],
            'Homeopathy' => ['BHMS', 'MD (Homeopathy)'],
            'Gastroenterology' => ['MBBS', 'MD (Medicine)', 'DM (Gastroenterology)', 'DNB (Gastroenterology)'],
            'Urology' => ['MBBS', 'MS (Surgery)', 'MCh (Urology)', 'DNB (Urology)'],
            'Oncology' => ['MBBS', 'MD (Medicine)', 'DM (Medical Oncology)', 'MS (Surgery)', 'MCh (Surgical Oncology)'],
            'Pulmonology' => ['MBBS', 'MD (Pulmonary Medicine)', 'DNB (Respiratory Diseases)', 'DTCD'],
        ];

        return $map[$deptEn] ?? ['MBBS', 'MD / MS', 'DNB'];
    }

    protected static function filterDoctorRecords(array $records, string $cityName): array
    {
        $accepted = [];
        $rejected = 0;
        $city = strtolower(trim($cityName));
        foreach ($records as $row) {
            $first = trim((string)($row['first_name'] ?? ''));
            $last = trim((string)($row['last_name'] ?? ''));
            $fullName = trim("{$first} {$last}");
            $hospital = trim((string)($row['hospital_name_en'] ?? ''));
            $dept = trim((string)($row['department_name_en'] ?? ''));
            $profileUrl = trim((string)($row['profile_url'] ?? ''));
            $sourceUrl = trim((string)($row['source_url'] ?? ''));
            $rowCity = strtolower(trim((string)($row['city'] ?? $cityName)));

            if ($first === '' || strlen($fullName) < 3) {
                $rejected++;
                continue;
            }
            if (preg_match('/\b(test|demo|sample|lorem|ipsum|dummy)\b/i', $fullName . ' ' . $hospital)) {
                $rejected++;
                continue;
            }
            if ($rowCity !== '' && $rowCity !== $city) {
                $rejected++;
                continue;
            }
            if ($profileUrl === '' && ($hospital === '' || $dept === '')) {
                $rejected++;
                continue;
            }

            $score = 55;
            if ($profileUrl !== '') {
                $score += 12;
            }
            if ($hospital !== '') {
                $score += 8;
            }
            if ($dept !== '') {
                $score += 6;
            }
            if (!empty($row['registration_number'])) {
                $score += 15;
            }
            if (!empty($row['medical_council'])) {
                $score += 4;
            }
            if (!empty($row['phone'])) {
                $score += 3;
            }
            if ($sourceUrl !== '' && preg_match('/\.gov\.in|practo\.com|apollo247\.com|lybrate\.com|bajajfinservhealth\.in/i', $sourceUrl)) {
                $score += 6;
            }
            $score = min(99, max(0, $score));
            if ($score < 75) {
                $rejected++;
                continue;
            }

            $row['source_confidence_score'] = $row['source_confidence_score'] ?? $score;
            $row['source_verification_status'] = $row['source_verification_status'] ?? ($score >= 85 ? 'verified_active' : 'scraped_unverified');
            $row['source_last_seen_at'] = $row['source_last_seen_at'] ?? now();
            $row['is_verified'] = $score >= 85;
            $accepted[] = $row;
        }

        return [$accepted, $rejected];
    }

    protected static function filterHospitalRecords(array $records, string $cityName): array
    {
        $accepted = [];
        $rejected = 0;
        $city = strtolower(trim($cityName));
        foreach ($records as $row) {
            $name = trim((string)($row['name_en'] ?? ''));
            $address = trim((string)($row['address'] ?? $row['address_en'] ?? ''));
            $rowCity = strtolower(trim((string)($row['city'] ?? $cityName)));
            if ($name === '' || strlen($name) < 4) {
                $rejected++;
                continue;
            }
            if (preg_match('/\b(test|demo|sample|lorem|ipsum|dummy)\b/i', $name)) {
                $rejected++;
                continue;
            }
            if ($rowCity !== '' && $rowCity !== $city) {
                $rejected++;
                continue;
            }
            if (!preg_match('/hospital|clinic|medical|health|nursing|institute|aiims|sms|centre|center/i', $name)) {
                $rejected++;
                continue;
            }

            $row['city'] = $row['city'] ?? $cityName;
            $row['is_verified'] = (bool)($row['is_verified'] ?? true);
            $row['source_last_seen_at'] = $row['source_last_seen_at'] ?? now();
            $row['source_confidence_score'] = $row['source_confidence_score'] ?? (!empty($address) ? 90 : 80);
            $accepted[] = $row;
        }
        return [$accepted, $rejected];
    }

    protected static function filterBloodBankRecords(array $records, string $cityName): array
    {
        $accepted = [];
        $rejected = 0;
        $seen = [];
        $city = strtolower(trim($cityName));
        foreach ($records as $row) {
            $name = trim((string)($row['name_en'] ?? ''));
            $rowCity = strtolower(trim((string)($row['city'] ?? $cityName)));
            $phone = preg_replace('/\D+/', '', (string)($row['phone'] ?? ''));

            if ($name === '' || strlen($name) < 5 || !preg_match('/blood bank|transfusion/i', $name)) {
                $rejected++;
                continue;
            }
            if ($rowCity !== '' && $rowCity !== $city) {
                $rejected++;
                continue;
            }
            if ($phone !== '' && strlen($phone) < 10) {
                $rejected++;
                continue;
            }

            $key = strtolower($name . '|' . ($row['city'] ?? $cityName));
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $row['city'] = $row['city'] ?? $cityName;
            $row['is_verified'] = (bool)($row['is_verified'] ?? true);
            $row['source_last_seen_at'] = $row['source_last_seen_at'] ?? now();
            $row['source_confidence_score'] = $row['source_confidence_score'] ?? 90;
            $accepted[] = $row;
        }

        return [$accepted, $rejected];
    }
}
