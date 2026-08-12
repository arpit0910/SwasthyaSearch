<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;

class DoctorImportService
{
    public function importFile(string $filePath): int
    {
        return $this->importRows(SpreadsheetService::parseSpreadsheet($filePath));
    }

    public function importRows(array $rows): int
    {
        $count = 0;

        foreach ($rows as $row) {
            $normalizedRow = $this->normalizeRow($row);

            if (blank($normalizedRow['first_name'] ?? null) && blank($normalizedRow['doctor_full_name'] ?? null)) {
                continue;
            }

            $doctor = $this->resolveDoctor($normalizedRow);
            $department = $this->resolveDepartment($normalizedRow);
            $doctorPayload = $this->buildDoctorPayload($normalizedRow, $department);

            if ($doctor) {
                $doctor->update($doctorPayload);
            } else {
                $doctor = Doctor::create($doctorPayload);
            }

            if ($department) {
                $doctor->departments()->syncWithoutDetaching([$department->id]);
            }

            $this->syncHospitalLink($doctor, $normalizedRow);
            $count++;
        }

        return $count;
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[trim(strtolower((string) $key))] = $this->normalizeValue($value);
        }

        if (blank($normalized['first_name'] ?? null) && filled($normalized['doctor_full_name'] ?? null)) {
            $nameParts = preg_split('/\s+/', trim((string) $normalized['doctor_full_name']), 2) ?: [];
            $normalized['first_name'] = $nameParts[0] ?? null;
            $normalized['last_name'] = $nameParts[1] ?? ($normalized['last_name'] ?? null);
        }

        return $normalized;
    }

    private function normalizeValue(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $trimmed = trim($value);
        if ($trimmed === '') {
            return null;
        }

        $lower = strtolower($trimmed);
        if (in_array($lower, ['n/a', 'na', 'unknown', 'null', 'none', 'not provided'], true)) {
            return null;
        }

        return $trimmed;
    }

    private function resolveDoctor(array $row): ?Doctor
    {
        $doctorId = $row['id'] ?? $row['doctor_id'] ?? null;
        if (filled($doctorId) && ctype_digit((string) $doctorId)) {
            $doctor = Doctor::find((int) $doctorId);
            if ($doctor) {
                return $doctor;
            }
        }

        if (filled($row['registration_number'] ?? null)) {
            return Doctor::where('registration_number', $row['registration_number'])->first();
        }

        return null;
    }

    private function resolveDepartment(array $row): ?Department
    {
        $departmentId = $row['department_id'] ?? null;
        if (filled($departmentId) && ctype_digit((string) $departmentId)) {
            return Department::find((int) $departmentId);
        }

        $departmentName = $row['department_name_en'] ?? $row['specialization'] ?? null;
        if (blank($departmentName)) {
            return null;
        }

        return Department::where('name_en', 'like', '%' . $departmentName . '%')->first();
    }

    private function buildDoctorPayload(array $row, ?Department $department): array
    {
        return [
            'first_name' => $row['first_name'] ?? null,
            'last_name' => $row['last_name'] ?? '',
            'registration_number' => $row['registration_number'] ?? null,
            'department_id' => $department?->id,
            'medical_council' => $row['medical_council'] ?? null,
            'country_code_1' => $row['country_code_1'] ?? ($row['country_code'] ?? null),
            'country_code_2' => $row['country_code_2'] ?? null,
            'phone_1' => $row['phone_1'] ?? ($row['phone'] ?? ($row['mobile'] ?? null)),
            'phone_2' => $row['phone_2'] ?? null,
            'consultation_fee' => $this->toDecimal($row['consultation_fee'] ?? null),
            'experience_years' => $this->toInteger($row['experience_years'] ?? $row['years_of_experience'] ?? null),
            'education_degrees' => $this->toList($row['education_degrees'] ?? $row['qualification'] ?? null),
            'about_en' => $row['about_en'] ?? null,
            'about_hi' => $row['about_hi'] ?? null,
            'email' => $row['email'] ?? null,
            'website' => $row['website'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'pincode' => $row['pincode'] ?? null,
            'address_line1' => $row['address_line1'] ?? $row['full_address'] ?? null,
            'address_line2' => $row['address_line2'] ?? null,
            'landmark' => $row['landmark'] ?? $row['area_locality'] ?? null,
            'languages_spoken' => $this->toList($row['languages_spoken'] ?? null),
            'gender' => $row['gender'] ?? null,
            'latitude' => $this->toDecimal($row['latitude'] ?? null),
            'longitude' => $this->toDecimal($row['longitude'] ?? null),
            'specialization_summary' => $row['subspecialization'] ?? null,
            'is_verified' => $this->toBoolean($row['is_verified'] ?? $row['verification_status'] ?? null),
        ];
    }

    private function syncHospitalLink(Doctor $doctor, array $row): void
    {
        $hospital = $this->resolveHospital($row);
        if (! $hospital) {
            return;
        }

        $pivotData = [
            'external_link_id' => $row['doctor_hospital_link_id'] ?? null,
            'role' => $row['doctor_hospital_role'] ?? null,
            'consultation_mode' => $row['consultation_mode'] ?? null,
            'availability' => $row['availability'] ?? null,
            'days_of_week' => $row['days_of_week'] ?? null,
            'start_time' => $row['start_time'] ?? null,
            'end_time' => $row['end_time'] ?? null,
            'consultation_fee' => $this->toDecimal($row['hospital_consultation_fee'] ?? $row['consultation_fee'] ?? null),
        ];

        $existing = $doctor->hospitals()->where('hospital_id', $hospital->id)->exists();
        if ($existing) {
            $doctor->hospitals()->updateExistingPivot($hospital->id, $pivotData);
            return;
        }

        $doctor->hospitals()->attach($hospital->id, $pivotData);
    }

    private function resolveHospital(array $row): ?Hospital
    {
        $hospitalId = $row['hospital_id'] ?? null;
        if (filled($hospitalId) && ctype_digit((string) $hospitalId)) {
            $hospital = Hospital::find((int) $hospitalId);
            if ($hospital) {
                return $this->updateHospitalFromRow($hospital, $row);
            }
        }

        $hospitalName = $row['hospital_name'] ?? null;
        if (blank($hospitalName)) {
            return null;
        }

        $hospital = Hospital::query()
            ->where('name_en', $hospitalName)
            ->when(filled($row['hospital_city'] ?? null), fn ($query) => $query->where('city', $row['hospital_city']))
            ->first();

        if ($hospital) {
            return $this->updateHospitalFromRow($hospital, $row);
        }

        return Hospital::create($this->buildHospitalPayload($row));
    }

    private function buildHospitalPayload(array $row): array
    {
        return [
            'name_en' => $row['hospital_name'] ?? null,
            'name_hi' => $row['hospital_name'] ?? null,
            'type' => $row['hospital_type'] ?? null,
            'address' => null,
            'address_line1' => $row['hospital_address'] ?? null,
            'city' => $row['hospital_city'] ?? null,
            'state' => $row['hospital_state'] ?? null,
            'pincode' => $row['hospital_pincode'] ?? null,
            'country_code_1' => $row['hospital_country_code_1'] ?? null,
            'phone_1' => $row['hospital_phone'] ?? null,
            'latitude' => $this->toDecimal($row['latitude'] ?? null),
            'longitude' => $this->toDecimal($row['longitude'] ?? null),
            'is_verified' => $this->toBoolean($row['verification_status'] ?? null),
        ];
    }

    private function updateHospitalFromRow(Hospital $hospital, array $row): Hospital
    {
        $payload = array_filter(
            $this->buildHospitalPayload($row),
            static fn ($value) => ! blank($value)
        );

        if (! empty($payload)) {
            $hospital->fill($payload);
            if ($hospital->isDirty()) {
                $hospital->save();
            }
        }

        return $hospital;
    }

    private function toList(mixed $value): ?array
    {
        if (blank($value)) {
            return null;
        }

        $parts = preg_split('/[;,|]+/', (string) $value) ?: [];
        $parts = array_values(array_filter(array_map(
            fn ($part) => $this->normalizeValue($part),
            $parts
        )));

        return empty($parts) ? null : $parts;
    }

    private function toInteger(mixed $value): ?int
    {
        if (blank($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        if (preg_match('/\d+/', (string) $value, $matches) === 1) {
            return (int) $matches[0];
        }

        return null;
    }

    private function toDecimal(mixed $value): ?float
    {
        if (blank($value) || ! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function toBoolean(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (blank($value)) {
            return null;
        }

        $normalized = strtolower((string) $value);

        return match ($normalized) {
            '1', 'true', 'yes', 'verified' => true,
            '0', 'false', 'no', 'unverified' => false,
            default => null,
        };
    }
}
