@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Cached Medical Questions</h1>
            <p class="text-muted mb-0">Manage MedicalQA cache entries used by chatbot responses.</p>
        </div>
        <div>
            <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('admin.cached_medical_questions.create') }}">
                <i class="fa-solid fa-plus"></i> Add Cached Question
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.cached_medical_questions') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-8">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search by question, answer, or category..."
                    >
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.cached_medical_questions') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Question (EN / HI)</th>
                            <th>Answer (EN / HI)</th>
                            <th>Category</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            <tr>
                                <td class="ps-4" style="width: 30%;">
                                    <div class="fw-bold text-dark">{{ $question->question_en }}</div>
                                    <div class="text-muted fs-7">{{ $question->question_hi }}</div>
                                </td>
                                <td style="width: 45%;">
                                    <div class="text-dark mb-1 fs-7">{{ Str::limit($question->answer_en, 120) }}</div>
                                    <div class="text-muted fs-7">{{ Str::limit($question->answer_hi, 120) }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $question->category }}</span></td>
                                <td class="text-end pe-4">
                                    <a class="btn btn-sm btn-outline-secondary me-1" href="{{ route('admin.cached_medical_questions.show', $question) }}" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.cached_medical_questions.edit', $question) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.cached_medical_questions.destroy', $question) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this cached question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-database fs-1 mb-3 d-block"></i>
                                    No cached medical questions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Showing {{ $questions->firstItem() ?? 0 }} to {{ $questions->lastItem() ?? 0 }} of {{ $questions->total() }} results
        </div>
        <div>
            {{ $questions->links() }}
        </div>
    </div>
</div>

@endsection

@push('styles')
    <style>
        .page-item.active .page-link {
            background-color: #14b8a6;
            border-color: #14b8a6;
        }
    </style>
@endpush

