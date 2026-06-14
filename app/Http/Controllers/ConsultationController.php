<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(): View
    {
        return view('consultations.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
        ]);

        $consultation = Consultation::create([
            'patient_name' => trim($data['patient_name']),
            'status' => Consultation::STATUS_PENDING,
            'ice_candidates_patient' => [],
            'ice_candidates_doctor' => [],
        ]);

        return redirect()->route('consultations.room', $consultation->uuid);
    }

    public function room(string $uuid): View
    {
        $consultation = $this->findConsultation($uuid);

        return view('consultations.room', compact('consultation'));
    }

    public function poll(string $uuid): JsonResponse
    {
        $consultation = $this->findConsultation($uuid);

        return response()->json($this->serializeConsultation($consultation));
    }

    public function signal(Request $request, string $uuid): JsonResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:patient,doctor'],
            'sdp_offer' => ['nullable', 'array'],
            'sdp_answer' => ['nullable', 'array'],
            'ice_candidates' => ['nullable', 'array'],
            'status' => ['nullable', 'in:pending,active,completed'],
        ]);

        $consultation = DB::transaction(function () use ($data, $uuid) {
            $consultation = Consultation::query()
                ->where('uuid', $uuid)
                ->lockForUpdate()
                ->firstOrFail();

            if (isset($data['sdp_offer']) && $data['role'] === 'patient') {
                $consultation->sdp_offer = json_encode($data['sdp_offer'], JSON_UNESCAPED_SLASHES);
                if ($consultation->status === Consultation::STATUS_COMPLETED) {
                    $consultation->status = Consultation::STATUS_PENDING;
                }
            }

            if (isset($data['sdp_answer']) && $data['role'] === 'doctor') {
                $consultation->sdp_answer = json_encode($data['sdp_answer'], JSON_UNESCAPED_SLASHES);
                $consultation->status = Consultation::STATUS_ACTIVE;
            }

            if (!empty($data['ice_candidates'])) {
                if ($data['role'] === 'patient') {
                    $consultation->ice_candidates_patient = $this->mergeCandidates(
                        $consultation->ice_candidates_patient,
                        $data['ice_candidates']
                    );
                } else {
                    $consultation->ice_candidates_doctor = $this->mergeCandidates(
                        $consultation->ice_candidates_doctor,
                        $data['ice_candidates']
                    );
                }
            }

            if (!empty($data['status'])) {
                $consultation->status = $data['status'];
            }

            $consultation->save();

            return $consultation->fresh();
        });

        return response()->json([
            'ok' => true,
            'consultation' => $this->serializeConsultation($consultation),
        ]);
    }

    public function end(string $uuid): JsonResponse
    {
        $consultation = $this->findConsultation($uuid);
        $consultation->update([
            'status' => Consultation::STATUS_COMPLETED,
        ]);

        return response()->json([
            'ok' => true,
            'status' => Consultation::STATUS_COMPLETED,
        ]);
    }

    private function findConsultation(string $uuid): Consultation
    {
        return Consultation::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    private function serializeConsultation(Consultation $consultation): array
    {
        return [
            'id' => $consultation->id,
            'uuid' => $consultation->uuid,
            'patient_name' => $consultation->patient_name,
            'status' => $consultation->status,
            'sdp_offer' => $consultation->sdp_offer ? json_decode($consultation->sdp_offer, true) : null,
            'sdp_answer' => $consultation->sdp_answer ? json_decode($consultation->sdp_answer, true) : null,
            'ice_candidates_patient' => $consultation->ice_candidates_patient ?? [],
            'ice_candidates_doctor' => $consultation->ice_candidates_doctor ?? [],
            'created_at' => optional($consultation->created_at)->toIso8601String(),
            'updated_at' => optional($consultation->updated_at)->toIso8601String(),
        ];
    }

    private function mergeCandidates(?array $existing, array $incoming): array
    {
        $normalized = [];

        foreach (array_merge($existing ?? [], $incoming) as $candidate) {
            if (!is_array($candidate) || empty($candidate['candidate'])) {
                continue;
            }

            $key = md5(json_encode($candidate, JSON_UNESCAPED_SLASHES));
            $normalized[$key] = $candidate;
        }

        return array_values($normalized);
    }
}
