<?php

namespace App\Services;

use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DirectoryDeduplicationService
{
    public function dedupe(?string $city = null): array
    {
        return [
            'doctors_removed' => $this->dedupeDoctors($city),
            'hospitals_removed' => $this->dedupeHospitals($city),
            'blood_banks_removed' => $this->dedupeBloodBanks($city),
        ];
    }

    public function dedupeDoctors(?string $city = null): int
    {
        $query = Doctor::query()->with(['departments:id', 'hospitals:id']);

        if ($city) {
            $query->where('city', $city);
        }

        return $this->dedupeRecords(
            $query->get(),
            fn (Doctor $doctor) => $this->doctorKeys($doctor),
            fn (Doctor $canonical, Doctor $duplicate) => $this->mergeDoctor($canonical, $duplicate)
        );
    }

    public function dedupeHospitals(?string $city = null): int
    {
        $query = Hospital::query()->with('doctors:id');

        if ($city) {
            $query->where('city', $city);
        }

        return $this->dedupeRecords(
            $query->get(),
            fn (Hospital $hospital) => $this->hospitalKeys($hospital),
            fn (Hospital $canonical, Hospital $duplicate) => $this->mergeHospital($canonical, $duplicate)
        );
    }

    public function dedupeBloodBanks(?string $city = null): int
    {
        $query = BloodBank::query();

        if ($city) {
            $query->where('city', $city);
        }

        return $this->dedupeRecords(
            $query->get(),
            fn (BloodBank $bloodBank) => $this->bloodBankKeys($bloodBank),
            fn (BloodBank $canonical, BloodBank $duplicate) => $this->mergeSimpleRecord($canonical, $duplicate)
        );
    }

    private function dedupeRecords(Collection $records, callable $keyResolver, callable $mergeHandler): int
    {
        $groups = [];
        $keyMap = [];

        foreach ($records->sortBy(fn ($record) => -$this->recordScore($record)) as $record) {
            $keys = array_values(array_unique(array_filter($keyResolver($record))));
            $matchedIndex = null;

            foreach ($keys as $key) {
                if (isset($keyMap[$key])) {
                    $matchedIndex = $keyMap[$key];
                    break;
                }
            }

            if ($matchedIndex === null) {
                $matchedIndex = count($groups);
                $groups[$matchedIndex] = [$record];
            } else {
                $groups[$matchedIndex][] = $record;
            }

            foreach ($keys as $key) {
                $keyMap[$key] = $matchedIndex;
            }
        }

        $removed = 0;

        foreach ($groups as $group) {
            if (count($group) < 2) {
                continue;
            }

            $canonical = array_shift($group);

            foreach ($group as $duplicate) {
                $mergeHandler($canonical, $duplicate);
                $removed++;
            }
        }

        return $removed;
    }

    private function mergeDoctor(Doctor $canonical, Doctor $duplicate): void
    {
        DB::transaction(function () use ($canonical, $duplicate) {
            $this->mergeModelAttributes($canonical, $duplicate, [
                'first_name', 'last_name', 'department_id', 'registration_number', 'medical_council',
                'education_degrees', 'experience_years', 'about_en', 'about_hi', 'is_verified', 'email',
                'country_code_1', 'country_code_2', 'phone_1', 'phone_2', 'address_line1', 'address_line2',
                'landmark', 'city', 'state', 'pincode', 'latitude', 'longitude', 'website', 'date_of_birth',
                'gender', 'languages_spoken', 'consultation_fee', 'specialization_summary',
                'awards_recognitions', 'membership_fellowships', 'cashless_treatment_available',
                'accepts_ayushman_card', 'accepts_jan_aadhaar', 'rgahs_approved',
            ]);

            foreach ($duplicate->departments as $department) {
                $exists = DB::table('department_doctor')
                    ->where('doctor_id', $canonical->id)
                    ->where('department_id', $department->id)
                    ->exists();

                if (! $exists) {
                    DB::table('department_doctor')->insert([
                        'doctor_id' => $canonical->id,
                        'department_id' => $department->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $doctorHospitals = DB::table('doctor_hospital')->where('doctor_id', $duplicate->id)->get();
            foreach ($doctorHospitals as $pivot) {
                $exists = DB::table('doctor_hospital')
                    ->where('doctor_id', $canonical->id)
                    ->where('hospital_id', $pivot->hospital_id)
                    ->exists();

                if (! $exists) {
                    DB::table('doctor_hospital')->insert([
                        'doctor_id' => $canonical->id,
                        'hospital_id' => $pivot->hospital_id,
                        'days_of_week' => $pivot->days_of_week,
                        'start_time' => $pivot->start_time,
                        'end_time' => $pivot->end_time,
                        'consultation_fee' => $pivot->consultation_fee,
                        'created_at' => $pivot->created_at,
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('department_doctor')->where('doctor_id', $duplicate->id)->delete();
            DB::table('doctor_hospital')->where('doctor_id', $duplicate->id)->delete();
            $duplicate->delete();
        });
    }

    private function mergeHospital(Hospital $canonical, Hospital $duplicate): void
    {
        DB::transaction(function () use ($canonical, $duplicate) {
            $this->mergeModelAttributes($canonical, $duplicate, [
                'name_en', 'name_hi', 'type', 'address', 'address_line1', 'address_line2', 'landmark',
                'city', 'state', 'pincode', 'latitude', 'longitude', 'country_code_1', 'country_code_2',
                'phone_1', 'phone_2', 'is_verified', 'accepts_ayushman', 'accepts_janaadhaar',
                'accepts_cghs', 'is_cashless', 'cashless_schemes_list', 'cashless_treatment_available',
                'accepts_ayushman_card', 'accepts_jan_aadhaar', 'rgahs_approved',
            ]);

            $doctorHospitals = DB::table('doctor_hospital')->where('hospital_id', $duplicate->id)->get();
            foreach ($doctorHospitals as $pivot) {
                $exists = DB::table('doctor_hospital')
                    ->where('doctor_id', $pivot->doctor_id)
                    ->where('hospital_id', $canonical->id)
                    ->exists();

                if (! $exists) {
                    DB::table('doctor_hospital')->insert([
                        'doctor_id' => $pivot->doctor_id,
                        'hospital_id' => $canonical->id,
                        'days_of_week' => $pivot->days_of_week,
                        'start_time' => $pivot->start_time,
                        'end_time' => $pivot->end_time,
                        'consultation_fee' => $pivot->consultation_fee,
                        'created_at' => $pivot->created_at,
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::table('doctor_hospital')->where('hospital_id', $duplicate->id)->delete();
            $duplicate->delete();
        });
    }

    private function mergeSimpleRecord(BloodBank $canonical, BloodBank $duplicate): void
    {
        DB::transaction(function () use ($canonical, $duplicate) {
            $this->mergeModelAttributes($canonical, $duplicate, [
                'name_en', 'name_hi', 'address_en', 'address_hi', 'city', 'state', 'pincode',
                'latitude', 'longitude', 'country_code', 'phone', 'emergency_country_code',
                'emergency_phone', 'email', 'website', 'is_verified', 'source_name', 'source_url',
                'source_confidence_score', 'source_verification', 'source_last_seen_at',
                'source_metadata', 'is_24_7', 'is_government', 'component_facility',
                'apheresis_facility', 'available_blood_groups', 'last_updated_stock_at',
            ]);

            $duplicate->delete();
        });
    }

    private function mergeModelAttributes(object $canonical, object $duplicate, array $fields): void
    {
        $updates = [];

        foreach ($fields as $field) {
            $canonicalValue = $canonical->{$field} ?? null;
            $duplicateValue = $duplicate->{$field} ?? null;

            if ($this->shouldReplaceValue($canonicalValue, $duplicateValue)) {
                $updates[$field] = $duplicateValue;
                continue;
            }

            if (is_array($canonicalValue) || is_array($duplicateValue)) {
                $merged = $this->mergeArrays((array) $canonicalValue, (array) $duplicateValue);
                if ($merged !== (array) $canonicalValue) {
                    $updates[$field] = $merged;
                }
                continue;
            }

            if (is_bool($canonicalValue) || is_bool($duplicateValue)) {
                $mergedBool = (bool) $canonicalValue || (bool) $duplicateValue;
                if ($mergedBool !== (bool) $canonicalValue) {
                    $updates[$field] = $mergedBool;
                }
            }
        }

        if ($updates !== []) {
            $canonical->fill($updates);
            $canonical->save();
        }
    }

    private function doctorKeys(Doctor $doctor): array
    {
        $fullName = $this->normalizeText(trim($doctor->first_name . ' ' . $doctor->last_name));
        $city = $this->normalizeText($doctor->city);
        $phone = $this->normalizePhone($doctor->phone_1 ?? $doctor->phone ?? null);
        $registration = trim((string) ($doctor->registration_number ?? ''));
        $department = (string) ($doctor->department_id ?? '');

        $keys = [];

        if ($registration !== '' && ! preg_match('/^REG-[A-F0-9]{10}$/i', $registration)) {
            $keys[] = 'doctor:reg:' . $this->normalizeText($registration);
        }

        if ($fullName !== '' && $city !== '') {
            if ($phone !== '') {
                $keys[] = "doctor:identity:{$fullName}|{$city}|{$phone}";
            }

            if ($department !== '') {
                $keys[] = "doctor:identity:{$fullName}|{$city}|dept:{$department}";
            } else {
                $keys[] = "doctor:identity:{$fullName}|{$city}";
            }
        }

        return $keys;
    }

    private function hospitalKeys(Hospital $hospital): array
    {
        $name = $this->normalizeFacilityIdentity($hospital->name_en, 'hospital');
        $city = $this->normalizeText($hospital->city);
        $phone = $this->normalizePhone($hospital->phone_1 ?? $hospital->emergency_phone ?? null);
        $address = $this->normalizeText($hospital->address_line1);

        return array_filter([
            $name !== '' && $city !== '' ? "hospital:name:{$name}|{$city}" : null,
            $phone !== '' && $city !== '' ? "hospital:phone:{$phone}|{$city}" : null,
            $name !== '' && $address !== '' && $city !== '' ? "hospital:address:{$name}|{$address}|{$city}" : null,
        ]);
    }

    private function bloodBankKeys(BloodBank $bloodBank): array
    {
        $name = $this->normalizeFacilityIdentity($bloodBank->name_en, 'blood_bank');
        $city = $this->normalizeText($bloodBank->city);
        $phone = $this->normalizePhone($bloodBank->phone);

        return array_filter([
            $name !== '' && $city !== '' ? "blood-bank:name:{$name}|{$city}" : null,
            $phone !== '' && $city !== '' ? "blood-bank:phone:{$phone}|{$city}" : null,
        ]);
    }

    private function recordScore(object $record): int
    {
        $score = 0;

        foreach ($record->getAttributes() as $value) {
            if (is_bool($value)) {
                $score += $value ? 1 : 0;
            } elseif (is_string($value)) {
                $score += trim($value) !== '' ? 1 : 0;
            } elseif ($value !== null) {
                $score += 1;
            }
        }

        return $score;
    }

    private function shouldReplaceValue(mixed $canonicalValue, mixed $duplicateValue): bool
    {
        if ($duplicateValue === null) {
            return false;
        }

        if (is_string($canonicalValue)) {
            return trim($canonicalValue) === '' && trim((string) $duplicateValue) !== '';
        }

        if (is_array($canonicalValue)) {
            return empty($canonicalValue) && ! empty($duplicateValue);
        }

        if (is_numeric($canonicalValue) || is_numeric($duplicateValue)) {
            return (float) $canonicalValue <= 0 && (float) $duplicateValue > 0;
        }

        return $canonicalValue === null;
    }

    private function mergeArrays(array $left, array $right): array
    {
        return array_values(array_unique(array_filter(array_merge($left, $right), function ($value) {
            return $value !== null && $value !== '';
        })));
    }

    private function normalizeText(?string $value): string
    {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/i', ' ', $value) ?? $value;

        return trim(preg_replace('/\s+/', ' ', $value) ?? $value);
    }

    private function normalizeFacilityIdentity(?string $value, string $type): string
    {
        $value = $this->normalizeText($value);

        if ($value === '') {
            return '';
        }

        if ($type === 'blood_bank') {
            $value = preg_replace('/\b(blood bank|blood centre|blood center|transfusion centre|transfusion center|component unit)\b/', ' ', $value) ?? $value;
        }

        $value = preg_replace('/\b(jaipur|rajasthan)\b/', ' ', $value) ?? $value;
        $value = preg_replace('/\b(the|unit|centre|center|hospitals)\b/', ' ', $value) ?? $value;

        $aliases = [
            '/\bck birla hospitals?\s*rbh\b/' => 'rukmani birla hospital',
            '/\brbh\b/' => 'rukmani birla hospital',
            '/\brukmani birla\b/' => 'rukmani birla hospital',
            '/\bfortis escorts\b/' => 'fortis escorts hospital',
            '/\bsms\b/' => 'sawai man singh hospital',
            '/\bsawai man barkatullah\b/' => 'sawai man singh',
            '/\bsawai man singh sms hospital\b/' => 'sawai man singh hospital',
            '/\bsawai man singh hospital hospital\b/' => 'sawai man singh hospital',
        ];

        foreach ($aliases as $pattern => $replacement) {
            $value = preg_replace($pattern, $replacement, $value) ?? $value;
        }

        $value = preg_replace('/\b(hospital|medical college|medical|clinic)\b/', ' ', $value) ?? $value;
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? $value);

        if (str_contains($value, 'sawai man singh')) {
            return 'sawai man singh';
        }

        if (str_contains($value, 'rukmani birla')) {
            return 'rukmani birla';
        }

        if (str_contains($value, 'fortis escorts')) {
            return 'fortis escorts';
        }

        return $value;
    }

    private function normalizePhone(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value) ?? '';
    }
}
