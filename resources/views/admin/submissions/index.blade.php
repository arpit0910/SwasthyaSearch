@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">User Suggestions / Submissions</h1>
            <p class="text-muted mb-0">Validate and verify doctor and hospital suggestions submitted by visitors, then import them directly to directory.</p>
        </div>
        <div>
            <a href="{{ route('admin.submissions.export') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.submissions') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="type" class="visually-hidden">Type</label>
                    <select name="type" id="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="doctor" {{ request('type') === 'doctor' ? 'selected' : '' }}>Doctors</option>
                        <option value="hospital" {{ request('type') === 'hospital' ? 'selected' : '' }}>Hospitals</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="status" class="visually-hidden">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('admin.submissions') }}" class="btn btn-sm btn-light">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="submissionsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Type</th>
                            <th>Name</th>
                            <th>Contact / Location</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $sub)
                            <tr>
                                <td class="ps-4">
                                    @if($sub->type === 'doctor')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1">
                                            <i class="fa-solid fa-user-doctor me-1"></i> Doctor
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                            <i class="fa-solid fa-hospital me-1"></i> Hospital
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $sub->name }}</div>
                                    <div class="text-muted fs-7">Submitted: {{ $sub->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    @if($sub->phone)
                                        <div class="text-dark"><i class="fa-solid fa-phone fs-7 me-1.5 text-muted"></i>{{ $sub->phone }}</div>
                                    @endif
                                    <div class="text-muted fs-7"><i class="fa-solid fa-location-dot fs-7 me-1.5 text-muted"></i>{{ $sub->city }}</div>
                                </td>
                                <td>
                                    @php $details = $sub->details ?? []; @endphp
                                    <div class="fs-7 text-dark"><strong>Address:</strong> {{ $details['address'] ?? 'N/A' }}</div>
                                    @if($sub->type === 'doctor')
                                        <div class="fs-7 text-muted">
                                            <strong>Reg No:</strong> {{ $details['registration_number'] ?? 'N/A' }} |
                                            <strong>Specialization:</strong> {{ $details['specialization'] ?? 'N/A' }}
                                        </div>
                                    @else
                                        <div class="fs-7 text-muted">
                                            <strong>Type:</strong> {{ $details['hospital_type'] ?? 'N/A' }} |
                                            <strong>Ayushman:</strong> {{ !empty($details['accepts_ayushman']) ? 'Yes' : 'No' }} |
                                            <strong>Jan Aadhaar:</strong> {{ !empty($details['accepts_janaadhaar']) ? 'Yes' : 'No' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($sub->status === 'pending')
                                        <span class="badge bg-warning"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                    @elseif($sub->status === 'approved')
                                        <span class="badge badge-teal"><i class="fa-solid fa-check-double me-1"></i> Approved & Imported</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i> Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($sub->status === 'pending')
                                        <form action="{{ route('admin.submissions.import', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Verify & import this submission directly into your directory?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success me-1" title="Approve & Import">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.submissions.reject', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this submission?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                                <i class="fa-solid fa-times"></i> Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted fs-7">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fs-1 mb-3 d-block"></i>
                                    No suggestions found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($submissions->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .badge-teal {
            background-color: #14b8a6;
            color: #fff;
        }
    </style>
@endpush
