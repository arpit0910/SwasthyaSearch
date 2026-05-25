<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BloodBankController extends Controller
{
    public function index(Request $request)
    {
        $userLat = $request->filled('user_lat') ? (float)$request->input('user_lat') : null;
        $userLng = $request->filled('user_lng') ? (float)$request->input('user_lng') : null;
        $hasUserLocation = is_numeric($userLat) && is_numeric($userLng);

        $query = BloodBank::where('is_verified', true)->latest();

        // Filter by City - support multiple selections
        $cities = $request->input('city', []);
        if (!is_array($cities)) {
            $cities = ($cities && $cities !== 'All') ? [$cities] : [];
        }
        $cities = array_filter($cities); // Remove empty values

        if (!empty($cities)) {
            $query->whereIn('city', $cities);
        }

        // Filter by Blood Group - support multiple selections
        $bloodGroupFilters = $request->input('blood_group', []);
        if (!is_array($bloodGroupFilters)) {
            $bloodGroupFilters = ($bloodGroupFilters && $bloodGroupFilters !== 'All') ? [$bloodGroupFilters] : [];
        }
        $bloodGroupFilters = array_filter($bloodGroupFilters); // Remove empty values

        if (!empty($bloodGroupFilters)) {
            $query->where(function ($q) use ($bloodGroupFilters) {
                foreach ($bloodGroupFilters as $bg) {
                    $q->orWhereJsonContains('available_blood_groups', $bg);
                }
            });
        }

        // Filter by Facility - support multiple selections
        $facilities = $request->input('facility', []);
        if (!is_array($facilities)) {
            $facilities = ($facilities && $facilities !== 'All') ? [$facilities] : [];
        }
        $facilities = array_filter($facilities); // Remove empty values

        if (!empty($facilities)) {
            $query->where(function ($q) use ($facilities) {
                foreach ($facilities as $fac) {
                    if ($fac === '24x7') {
                        $q->where('is_24_7', true);
                    } elseif ($fac === 'Government') {
                        $q->where('is_government', true);
                    } elseif ($fac === 'Private') {
                        $q->where('is_government', false);
                    } elseif ($fac === 'Component') {
                        $q->where('component_facility', true);
                    } elseif ($fac === 'Apheresis') {
                        $q->where('apheresis_facility', true);
                    }
                }
            });
        }

        // Filter by Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'LIKE', "%{$search}%")
                    ->orWhere('name_hi', 'LIKE', "%{$search}%")
                    ->orWhere('address_en', 'LIKE', "%{$search}%")
                    ->orWhere('address_hi', 'LIKE', "%{$search}%")
                    ->orWhereJsonContains('available_blood_groups', $search);
            });
        }

        $cities = BloodBank::where('is_verified', true)->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city');
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        // Get all blood banks first
        $bloodBanks = $query->get();
        
        // If user location provided, calculate distances and sort
        if ($hasUserLocation) {
            $nearbyBloodBanks = $bloodBanks->map(fn(BloodBank $bank) => $this->formatBloodBank($bank, $userLat, $userLng))
                ->sortBy('distance_km')
                ->filter(fn($b) => $b['distance_km'] !== null && $b['distance_km'] < 50)
                ->values();
            $bloodBanks = $this->paginateCollection($nearbyBloodBanks, $request, 30);
        } else {
            $bloodBanks = $query
                ->paginate(30)
                ->through(fn(BloodBank $bank) => $this->formatBloodBank($bank, $userLat, $userLng));
        }

        return view('blood_banks.index', [
            'bloodBanks' => $bloodBanks,
            'cities' => $cities,
            'bloodGroups' => $bloodGroups,
            'filters' => $request->only(['city', 'blood_group', 'facility', 'search', 'user_lat', 'user_lng']),
            'hasUserLocation' => $hasUserLocation,
        ]);
    }

    private function formatBloodBank(BloodBank $bank, ?float $userLat = null, ?float $userLng = null): array
    {
        return [
            'id' => $bank->id,
            'name' => [
                'en' => $bank->name_en,
                'hi' => $bank->name_hi,
            ],
            'address' => [
                'en' => $bank->address_en ?: '',
                'hi' => $bank->address_hi ?: '',
            ],
            'city' => $bank->city,
            'state' => $bank->state,
            'pincode' => $bank->pincode,
            'latitude' => $bank->latitude,
            'longitude' => $bank->longitude,
            'country_code' => $bank->country_code,
            'phone' => $bank->phone,
            'emergency_country_code' => $bank->emergency_country_code,
            'emergency_phone' => $bank->emergency_phone,
            'email' => $bank->email,
            'website' => $bank->website,
            'is_verified' => $bank->is_verified,
            'is_24_7' => $bank->is_24_7,
            'is_government' => $bank->is_government,
            'component_facility' => $bank->component_facility,
            'apheresis_facility' => $bank->apheresis_facility,
            'available_blood_groups' => $bank->available_blood_groups ?: [],
            'last_updated_stock_at' => $bank->last_updated_stock_at,
            'distance_km' => $this->calculateDistanceKm($userLat, $userLng, $bank->latitude, $bank->longitude),
        ];
    }

    private function calculateDistanceKm(?float $userLat, ?float $userLng, $targetLat, $targetLng): ?float
    {
        if (!is_numeric($userLat) || !is_numeric($userLng) || !is_numeric($targetLat) || !is_numeric($targetLng)) {
            return null;
        }

        $earthRadius = 6371;
        $latFrom = deg2rad((float)$userLat);
        $lonFrom = deg2rad((float)$userLng);
        $latTo = deg2rad((float)$targetLat);
        $lonTo = deg2rad((float)$targetLng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 1);
    }

    private function paginateCollection($items, Request $request, int $perPage = 30): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($items);
        $slice = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
