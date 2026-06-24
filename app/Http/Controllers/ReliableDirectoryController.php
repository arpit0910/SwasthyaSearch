<?php

namespace App\Http\Controllers;

use App\Services\ReliableHealthcareDirectoryService;
use Illuminate\Http\Request;

class ReliableDirectoryController extends Controller
{
    public function doctors(Request $request)
    {
        $activeCity = config('healthcare.active_city', 'Jaipur');
        $validated = $request->validate([
            'city' => 'nullable|string|max:120',
            'department' => 'nullable|string|max:120',
        ]);

        $doctors = ReliableHealthcareDirectoryService::doctorsByCity($validated['city'] ?? $activeCity, $validated['department'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => $doctors->isEmpty() ? 'No reliable doctor records found for the selected filters.' : null,
            'data' => $doctors->map(fn($d) => [
                'id' => $d->id,
                'first_name' => $d->first_name,
                'last_name' => $d->last_name,
                'department' => $d->department?->name_en,
                'registration_number' => $d->registration_number,
                'medical_council' => $d->medical_council,
                'phone_1' => $d->phone_1,
                'phone_2' => $d->phone_2,
                'country_code_1' => $d->country_code_1,
                'country_code_2' => $d->country_code_2,
                'address_line1' => $d->address_line1,
                'address_line2' => $d->address_line2,
                'city' => $d->city,
                'state' => $d->state,
                'pincode' => $d->pincode,
                'latitude' => $d->latitude,
                'longitude' => $d->longitude,
                'source_name' => $d->source_name,
                'source_url' => $d->source_url,
            ])->values(),
        ]);
    }

    public function hospitals(Request $request)
    {
        $activeCity = config('healthcare.active_city', 'Jaipur');
        $validated = $request->validate([
            'city' => 'nullable|string|max:120',
        ]);

        $hospitals = ReliableHealthcareDirectoryService::hospitalsByCity($validated['city'] ?? $activeCity);

        return response()->json([
            'status' => 'success',
            'message' => $hospitals->isEmpty() ? 'No reliable hospital records found for the selected city.' : null,
            'data' => $hospitals->map(fn($h) => [
                'id' => $h->id,
                'name_en' => $h->name_en,
                'name_hi' => $h->name_hi,
                'type' => $h->type,
                'address' => $h->address,
                'address_line1' => $h->address_line1,
                'address_line2' => $h->address_line2,
                'city' => $h->city,
                'state' => $h->state,
                'pincode' => $h->pincode,
                'phone_1' => $h->phone_1,
                'phone_2' => $h->phone_2,
                'country_code_1' => $h->country_code_1,
                'country_code_2' => $h->country_code_2,
                'latitude' => $h->latitude,
                'longitude' => $h->longitude,
                'source_name' => $h->source_name,
                'source_url' => $h->source_url,
            ])->values(),
        ]);
    }

    public function bloodBanks(Request $request)
    {
        $activeCity = config('healthcare.active_city', 'Jaipur');
        $validated = $request->validate([
            'city' => 'nullable|string|max:120',
        ]);

        $banks = ReliableHealthcareDirectoryService::bloodBanksByCity($validated['city'] ?? $activeCity);

        return response()->json([
            'status' => 'success',
            'message' => $banks->isEmpty() ? 'No reliable blood bank records found for the selected city.' : null,
            'data' => $banks->map(fn($b) => [
                'id' => $b->id,
                'name_en' => $b->name_en,
                'name_hi' => $b->name_hi,
                'address_en' => $b->address_en,
                'address_hi' => $b->address_hi,
                'city' => $b->city,
                'state' => $b->state,
                'pincode' => $b->pincode,
                'phone' => $b->phone,
                'country_code' => $b->country_code,
                'emergency_phone' => $b->emergency_phone,
                'emergency_country_code' => $b->emergency_country_code,
                'source_name' => $b->source_name,
                'source_url' => $b->source_url,
            ])->values(),
        ]);
    }
}
