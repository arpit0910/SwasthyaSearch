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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="cachedMedicalQuestionsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Question (EN / HI)</th>
                            <th>Answer (EN / HI)</th>
                            <th>Category</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $question)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .row {
            margin-bottom: 0.75rem;
            align-items: center;
        }
        .dataTables_length select {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.25rem 0.5rem;
        }
        .dataTables_filter input {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.35rem 0.75rem;
            outline: none;
        }
        .dataTables_filter input:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2);
        }
        .page-item.active .page-link {
            background-color: #14b8a6;
            border-color: #14b8a6;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cachedMedicalQuestionsTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search cached questions...",
                    emptyTable: '<div class="text-center py-5 text-muted"><i class="fa-solid fa-database fs-1 mb-3 d-block"></i>No cached medical questions found.</div>'
                }
            });
        });
    </script>
@endpush

