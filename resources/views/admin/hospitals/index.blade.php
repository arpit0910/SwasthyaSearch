@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Hospitals Directory</h1>
            <p class="text-muted mb-0">Manage registered hospitals, clinics, and medical centers.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add Hospital
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.hospitals') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search hospitals by name or city..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.hospitals') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Hospitals Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Name (EN / HI)</th>
                        <th>Type</th>
                        <th>City</th>
                        <th>Emergency Phone</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hospitals as $hospital)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $hospital->getTranslation('name', 'en') }}</div>
                                <div class="text-muted fs-7">{{ $hospital->getTranslation('name', 'hi') }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $hospital->type }}</span></td>
                            <td>{{ $hospital->city }}</td>
                            <td><span class="font-monospace">{{ $hospital->emergency_phone }}</span></td>
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
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-hospital-user fs-1 mb-3 d-block"></i>
                                No hospitals found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
            {{ $hospitals->links() }}
        </div>
    </div>
</div>

@include('admin.hospitals.create')
@include('admin.hospitals.import')

@endsection
