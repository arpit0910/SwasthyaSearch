@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create General Qa</h1>
        <a href="{{ route('admin.general_qa') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.general_qa.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" required placeholder="What should I do if I have a headache?">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" required placeholder="अगर सिर दर्द हो तो क्या करें?">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="4" required placeholder="Rest, hydrate, reduce screen strain..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="4" required placeholder="आराम करें, पानी पिएं, स्क्रीन कम देखें..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (English)</label>
                            <textarea name="detailed_answer_en" class="form-control" rows="4" placeholder="Optional detailed explanation for 'Explain in Detail'"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (Hindi)</label>
                            <textarea name="detailed_answer_hi" class="form-control" rows="4" placeholder="Optional detailed explanation for '? ?? ??'"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.general_qa') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create</button>
                </div>
            </form>
    </div>
</div>
@endsection




