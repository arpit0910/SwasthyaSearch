<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HealthcareQueryController extends Controller
{
    /**
     * Sample API Resource demonstrating how a user queries "Nearest Doctors accepting Jan Aadhaar"
     * or other specific healthcare schemes with geospatial proximity filtering.
     *
     * GET /api/doctors/nearest?latitude=26.9124&longitude=75.7873&scheme=jan_aadhaar&radius=50
     */
    public function nearestDoctors(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:500',
            'scheme' => 'nullable|string|in:jan_aadhaar,ayushman,rgahs,cashless',
        ]);

        $lat = (float) $request->input('latitude');
        $lng = (float) $request->input('longitude');
        $radius = (float) $request->input('radius', 50); // Default 50 km radius
        $scheme = $request->input('scheme', 'jan_aadhaar');

        // Query doctors within distance using Haversine geospatial query scope
        $query = Doctor::with(['departments', 'hospitals'])
            ->closeTo($lat, $lng, $radius);

        // Filter by Scheme (either accepted at the doctor's independent clinic OR at their associated hospitals)
        if ($scheme === 'jan_aadhaar') {
            $query->where(function ($q) {
                $q->where('accepts_jan_aadhaar', true)
                  ->orWhereHas('hospitals', function ($hospQuery) {
                      $hospQuery->where('accepts_jan_aadhaar', true)
                                ->orWhere('accepts_janaadhaar', true);
                  });
            });
        } elseif ($scheme === 'ayushman') {
            $query->where(function ($q) {
                $q->where('accepts_ayushman_card', true)
                  ->orWhereHas('hospitals', function ($hospQuery) {
                      $hospQuery->where('accepts_ayushman_card', true)
                                ->orWhere('accepts_ayushman', true);
                  });
            });
        } elseif ($scheme === 'rgahs') {
            $query->where(function ($q) {
                $q->where('rgahs_approved', true)
                  ->orWhereHas('hospitals', function ($hospQuery) {
                      $hospQuery->where('rgahs_approved', true)
                                ->orWhere('accepts_cghs', true);
                  });
            });
        } elseif ($scheme === 'cashless') {
            $query->where(function ($q) {
                $q->where('cashless_treatment_available', true)
                  ->orWhereHas('hospitals', function ($hospQuery) {
                      $hospQuery->where('cashless_treatment_available', true)
                                ->orWhere('is_cashless', true);
                  });
            });
        }

        // Paginate results
        $doctors = $query->paginate(15);

        // Transform results to provide clean, rich API payload including distance and scheme tags E.g. for frontend UI
        $transformed = $doctors->through(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => "Dr. {$doctor->first_name} " . ($doctor->last_name ?? ''),
                'departments' => $doctor->departments->pluck('name_en')->toArray(),
                'experience_years' => $doctor->experience_years,
                'consultation_fee' => $doctor->consultation_fee,
                'address' => [
                    'line1' => $doctor->address_line1,
                    'line2' => $doctor->address_line2,
                    'city' => $doctor->city,
                    'state' => $doctor->state,
                    'pincode' => $doctor->pincode,
                ],
                'coordinates' => [
                    'latitude' => $doctor->latitude,
                    'longitude' => $doctor->longitude,
                ],
                'distance_km' => round($doctor->distance, 2),
                'schemes_accepted' => [
                    'jan_aadhaar' => $doctor->accepts_jan_aadhaar || $doctor->hospitals->contains(fn($h) => $h->accepts_jan_aadhaar || $h->accepts_janaadhaar),
                    'ayushman_card' => $doctor->accepts_ayushman_card || $doctor->hospitals->contains(fn($h) => $h->accepts_ayushman_card || $h->accepts_ayushman),
                    'rgahs_approved' => $doctor->rgahs_approved || $doctor->hospitals->contains(fn($h) => $h->rgahs_approved || $h->accepts_cghs),
                    'cashless_treatment' => $doctor->cashless_treatment_available || $doctor->hospitals->contains(fn($h) => $h->cashless_treatment_available || $h->is_cashless),
                ],
                'hospitals' => $doctor->hospitals->map(function ($hosp) {
                    return [
                        'id' => $hosp->id,
                        'name' => $hosp->name_en,
                        'type' => $hosp->type,
                        'cashless_schemes_list' => $hosp->cashless_schemes_list,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'query' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'radius_km' => $radius,
                'scheme_filter' => $scheme,
            ],
            'data' => $transformed,
        ]);
    }
}
