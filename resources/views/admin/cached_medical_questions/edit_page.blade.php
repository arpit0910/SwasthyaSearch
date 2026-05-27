@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Edit Cached Medical Questions</h1>
        <a href="{{ route('admin.cached_medical_questions') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.cached_medical_questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" value="{{ $question->question_en }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" value="{{ $question->question_hi }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="4" required>{{ $question->answer_en }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="4" required>{{ $question->answer_hi }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (English)</label>
                            <textarea name="detailed_answer_en" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle">{{ $question->detailed_answer_en }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (Hindi)</label>
                            <textarea name="detailed_answer_hi" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle">{{ $question->detailed_answer_hi }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ $question->category }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.cached_medical_questions') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
    </div>
</div>
@endsection




