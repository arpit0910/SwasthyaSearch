@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Hospitals Directory</h1>
            <p class="text-muted mb-0">Manage registered hospitals, clinics, and medical centers.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#syncModal">
                <i class="fa-solid fa-rotate"></i> Sync Hospitals
            </button>
            <a href="{{ route('admin.hospitals.export') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export CSV
            </a>
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add Hospital
            </button>
        </div>
    </div>

        <!-- Hospitals Table -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table id="hospitalsTable" class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name (EN / HI)</th>
                                <th>Type</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Phone Numbers</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hospitals as $hospital)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $hospital->getTranslation('name', 'en') }}</div>
                                        <div class="text-muted fs-7">{{ $hospital->getTranslation('name', 'hi') }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $hospital->type }}</span></td>
                                    <td>{{ $hospital->city }}</td>
                                    <td>
                                        <div class="small text-dark">{{ $hospital->address_line1 ?: 'N/A' }}</div>
                                        @if(!empty($hospital->address_line2))
                                            <div class="small text-muted">{{ $hospital->address_line2 }}</div>
                                        @endif
                                        @if(!empty($hospital->landmark))
                                            <div class="small text-muted">Landmark: {{ $hospital->landmark }}</div>
                                        @endif
                                        @php
                                            $locationParts = array_filter([
                                                $hospital->city,
                                                $hospital->state,
                                                $hospital->pincode
                                            ]);
                                        @endphp
                                        @if(!empty($locationParts))
                                            <div class="small text-muted">{{ implode(', ', $locationParts) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $hospitalPhones = [];
                                            if (!empty($hospital->phone_1)) {
                                                $hospitalPhones[] = trim(($hospital->country_code_1 ?? '') . ' ' . $hospital->phone_1);
                                            }
                                            if (!empty($hospital->phone_2)) {
                                                $hospitalPhones[] = trim(($hospital->country_code_2 ?? $hospital->country_code_1 ?? '') . ' ' . $hospital->phone_2);
                                            }
                                            if (empty($hospitalPhones) && !empty($hospital->phone)) {
                                                $hospitalPhones[] = $hospital->phone;
                                            }
                                        @endphp
                                        @if(!empty($hospitalPhones))
                                            @foreach($hospitalPhones as $num)
                                                <div class="font-monospace small">{{ $num }}</div>
                                            @endforeach
                                        @else
                                            <span class="text-muted fst-italic fs-7">Not Available</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hospital->is_verified)
                                            <span class="badge badge-teal"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $hospital->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('admin.hospitals.destroy', $hospital) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this hospital?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                @include('admin.hospitals.edit', ['hospital' => $hospital])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.hospitals.create')
    @include('admin.hospitals.import')
    @include('admin.hospitals.sync')

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
            $('#hospitalsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search hospitals...",
                    "emptyTable": '<div class="text-center py-5 text-muted"><i class="fa-solid fa-hospital-user fs-1 mb-3 d-block"></i>No hospitals found matching your criteria.</div>'
                }
            });
        });
    </script>
@endpush
