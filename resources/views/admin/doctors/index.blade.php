@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">Doctors Directory</h1>
                <p class="text-muted mb-0">Manage registered healthcare professionals and specialists.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#syncModal">
                    <i class="fa-solid fa-rotate"></i> Sync Doctors
                </button>
                <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#importModal">
                    <i class="fa-solid fa-file-import"></i> Import CSV
                </button>
                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#createModal">
                    <i class="fa-solid fa-plus"></i> Add Doctor
                </button>
            </div>
        </div>

        <!-- Doctors Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table id="doctorsTable" class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Doctor Name</th>
                                <th>Specialty / Dept</th>
                                <th>Registration No.</th>
                                <th>Experience</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </div>
                                        <div class="text-muted fs-7">
                                            {{ is_array($doctor->education_degrees) ? implode(', ', $doctor->education_degrees) : $doctor->education_degrees }}
                                        </div>
                                    </td>
                                    <td>
                                        @forelse($doctor->departments as $dept)
                                            <span
                                                class="badge bg-light text-dark border me-1">{{ $dept->getTranslation('name', 'en') }}</span>
                                        @empty
                                            <span
                                                class="badge bg-light text-dark border">{{ $doctor->department?->getTranslation('name', 'en') ?? 'General' }}</span>
                                        @endforelse
                                    </td>
                                    <td><span class="font-monospace">{{ $doctor->registration_number }}</span></td>
                                    <td>{{ $doctor->experience_years }} years</td>
                                    <td>
                                        @if ($doctor->is_verified)
                                            <span class="badge badge-teal"><i class="fa-solid fa-circle-check me-1"></i>
                                                Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>
                                                Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $doctor->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                @include('admin.doctors.edit', ['doctor' => $doctor])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.doctors.create')
    @include('admin.doctors.import')
    @include('admin.doctors.sync')

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
            $('#doctorsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search doctors...",
                    "emptyTable": '<div class="text-center py-5 text-muted"><i class="fa-solid fa-user-doctor fs-1 mb-3 d-block"></i>No doctors found matching your criteria.</div>'
                }
            });
        });
    </script>
@endpush
