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
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add Department
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.departments') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search departments by name..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Department Name (EN / HI)</th>
                        <th>Description (EN / HI)</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
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
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $department->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.departments.edit', ['department' => $department])
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-layer-group fs-1 mb-3 d-block"></i>
                                No departments found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
            {{ $departments->links() }}
        </div>
    </div>
</div>

@include('admin.departments.create')
@include('admin.departments.import')

@endsection
