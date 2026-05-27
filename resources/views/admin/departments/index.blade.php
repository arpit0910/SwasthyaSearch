@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Departments / Specialties</h1>
            <p class="text-muted mb-0">Manage medical specialties used for AI symptom matching and categorization.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Department
            </a>
        </div>
    </div>

        <!-- Departments Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table id="departmentsTable" class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Department Name (EN / HI)</th>
                                <th>Description (EN / HI)</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                                <tr>
                                    <td class="ps-4" style="width: 25%;">
                                        <div class="fw-bold text-dark">{{ $department->getTranslation('name', 'en') }}</div>
                                        <div class="text-muted fs-7">{{ $department->getTranslation('name', 'hi') }}</div>
                                    </td>
                                    <td style="width: 50%;">
                                        <div class="text-dark mb-1 fs-7">{{ Str::limit($department->getTranslation('description', 'en'), 80) }}</div>
                                        <div class="text-muted fs-7">{{ Str::limit($department->getTranslation('description', 'hi'), 80) }}</div>
                                    </td>
                                    <td>
                                        @if($department->is_active)
                                            <span class="badge badge-teal"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fa-solid fa-circle-xmark me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
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

    @include('admin.departments.import')

@endsection

@push('styles')
    <!-- DataTables Bootstrap 5 CSS -->
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
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#departmentsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search departments...",
                    "emptyTable": '<div class="text-center py-5 text-muted"><i class="fa-solid fa-layer-group fs-1 mb-3 d-block"></i>No departments found matching your criteria.</div>'
                }
            });
        });
    </script>
@endpush

