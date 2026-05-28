@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">View Cached Medical Question</h1>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.cached_medical_questions.edit', $cachedMedicalQuestion) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.cached_medical_questions') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label fw-semibold text-muted">Category</label>
                    <div class="fw-semibold">{{ $cachedMedicalQuestion->category }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Question (English)</label>
                    <div>{{ $cachedMedicalQuestion->question_en }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Question (Hindi)</label>
                    <div>{{ $cachedMedicalQuestion->question_hi }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Answer (English)</label>
                    <div class="border rounded p-3 bg-light-subtle">{{ $cachedMedicalQuestion->answer_en }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Answer (Hindi)</label>
                    <div class="border rounded p-3 bg-light-subtle">{{ $cachedMedicalQuestion->answer_hi }}</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Detailed Answer (English)</label>
                    <div class="border rounded p-3 bg-light-subtle">{{ $cachedMedicalQuestion->detailed_answer_en ?: 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Detailed Answer (Hindi)</label>
                    <div class="border rounded p-3 bg-light-subtle">{{ $cachedMedicalQuestion->detailed_answer_hi ?: 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
