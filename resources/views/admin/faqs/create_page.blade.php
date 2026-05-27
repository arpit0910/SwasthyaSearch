@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Faqs</h1>
        <a href="{{ route('admin.faqs') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" required placeholder="How do I book an appointment?">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" required placeholder="मैं अपॉइंटमेंट कैसे बुक करूं?">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="3" required placeholder="You can book directly from the doctor profile page..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="3" required placeholder="आप डॉक्टर की प्रोफ़ाइल से सीधे बुक कर सकते हैं..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" required placeholder="Appointments">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.faqs') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create FAQ</button>
                </div>
            </form>
    </div>
</div>
@endsection




