@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Edit Diseases</h1>
        <a href="{{ route('admin.diseases') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.diseases.update', $disease) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Disease Name (English)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ $disease->getTranslation('name', 'en') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Disease Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" value="{{ $disease->getTranslation('name', 'hi') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Symptoms (English)</label>
                            <textarea name="symptoms_en" class="form-control" rows="2">{{ $disease->symptoms->pluck('name_en')->implode(', ') }}</textarea>
                            <small class="text-muted">Use comma, semicolon, or new line to separate symptoms.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Symptoms (Hindi)</label>
                            <textarea name="symptoms_hi" class="form-control" rows="2">{{ $disease->symptoms->pluck('name_hi')->filter()->implode(', ') }}</textarea>
                            <small class="text-muted">Order should match English symptoms if provided.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Matched Medical Department</label>
                            <select name="department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $disease->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.diseases') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
    </div>
</div>
@endsection



