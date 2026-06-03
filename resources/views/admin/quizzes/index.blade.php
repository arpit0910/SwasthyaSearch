@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Quiz Management</h1>
            <p class="text-muted mb-0">Manage awareness quizzes and publish safe self-reflection content.</p>
        </div>
        <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('admin.quizzes.create') }}">
            <i class="fa-solid fa-plus"></i> Add Quiz
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-3 mb-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search quizzes by title or category">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Search</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Quiz</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $quiz->title_en }}</div>
                                    <div class="text-muted small">{{ $quiz->title_hi }}</div>
                                </td>
                                <td>{{ $quiz->category ?: '—' }}</td>
                                <td>
                                    @if($quiz->is_published)
                                        <span class="badge badge-teal">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.quizzes.edit', $quiz) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="d-inline" onsubmit="return confirm('Delete this quiz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No quizzes found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $quizzes->links() }}</div>
        </div>
    </div>
</div>
@endsection
