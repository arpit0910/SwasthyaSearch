@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Blood Banks Directory</h1>
            <p class="text-muted mb-0">Manage registered blood banks, emergency blood stock, and apheresis centers.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#syncModal">
                <i class="fa-solid fa-rotate"></i> Sync Blood Banks
            </button>
            <a href="{{ route('admin.blood_banks.export') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export CSV
            </a>
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <a href="{{ route('admin.blood_banks.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Blood Bank
            </a>
        </div>
    </div>

    <!-- Blood Banks Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="bloodBanksTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Name (EN / HI)</th>
                            <th>Facility Type</th>
                            <th>City</th>
                            <th>Helpline</th>
                            <th>Available Blood Groups</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bloodBanks as $bb)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $bb->getTranslation('name', 'en') }}</div>
                                    <div class="text-muted fs-7">{{ $bb->getTranslation('name', 'hi') }}</div>
                                    @if(!empty($bb->landmark))
                                        <div class="text-muted fs-7">Landmark: {{ $bb->landmark }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($bb->is_government)
                                        <span class="badge bg-danger text-white border me-1"><i class="fa-solid fa-building-flag me-1"></i> Govt Blood Bank</span>
                                    @else
                                        <span class="badge bg-primary text-white border me-1"><i class="fa-solid fa-hospital me-1"></i> Private Blood Bank</span>
                                    @endif
                                    @if($bb->is_24_7)
                                        <span class="badge bg-dark text-white border"><i class="fa-solid fa-clock me-1"></i> 24/7</span>
                                    @endif
                                </td>
                                <td>{{ $bb->city }}</td>
                                <td>
                                    <div class="font-monospace fs-7">{{ '+' . ltrim(($bb->emergency_country_code ?? '91'), '+') }} {{ $bb->emergency_phone }}</div>
                                </td>
                                <td>
                                    @if(is_array($bb->available_blood_groups))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($bb->available_blood_groups as $bg)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">{{ $bg }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fs-7">Not updated</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bb->is_verified)
                                        <span class="badge badge-teal"><i class="fa-solid fa-circle-check me-1"></i> Verified</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.blood_banks.edit', $bb) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.blood_banks.destroy', $bb) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blood bank?');">
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

@include('admin.blood_banks.import')
@include('admin.blood_banks.sync')

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
            $('#bloodBanksTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search blood banks...",
                    "emptyTable": '<div class="text-center py-5 text-muted"><i class="fa-solid fa-droplet fs-1 mb-3 d-block text-danger"></i>No blood banks found matching your criteria.</div>'
                }
            });
        });
    </script>
@endpush

