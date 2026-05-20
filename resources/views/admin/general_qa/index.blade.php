@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">General Medical Q&A</h1>
            <p class="text-muted mb-0">Manage chatbot-ready guidance for common symptoms and first-aid questions.</p>
        </div>
        <div>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add Question
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="generalQaTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Question (EN / HI)</th>
                            <th>Answer (EN / HI)</th>
                            <th>Category</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $faq)
                            <tr>
                                <td class="ps-4" style="width: 30%;">
                                    <div class="fw-bold text-dark">{{ $faq->getTranslation('question', 'en') }}</div>
                                    <div class="text-muted fs-7">{{ $faq->getTranslation('question', 'hi') }}</div>
                                </td>
                                <td style="width: 45%;">
                                    <div class="text-dark mb-1 fs-7">{{ Str::limit($faq->getTranslation('answer', 'en'), 120) }}</div>
                                    <div class="text-muted fs-7">{{ Str::limit($faq->getTranslation('answer', 'hi'), 120) }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">General Medical</span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $faq->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.general_qa.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            @include('admin.general_qa.edit', ['faq' => $faq])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.general_qa.create')

@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#generalQaTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search questions...",
                    emptyTable: '<div class="text-center py-5 text-muted"><i class="fa-solid fa-circle-question fs-1 mb-3 d-block"></i>No general Q&A entries found.</div>'
                }
            });
        });
    </script>
@endpush

