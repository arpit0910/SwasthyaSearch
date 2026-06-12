@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Medicine Information Management</h1>
            <p class="text-muted mb-0">Manage medicine education pages, publish safe content, and control review workflow.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.medicines.export') }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Export CSV
            </a>
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('admin.medicines.create') }}">
                <i class="fa-solid fa-plus"></i> Add Medicine
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-3 mb-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search medicine, brand, generic name, or composition">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(\App\Models\Medicine::REVIEW_STATUSES as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-primary w-100">Go</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $medicine->name }}</div>
                                    <div class="text-muted small">{{ $medicine->generic_name ?: 'No generic name' }}</div>
                                </td>
                                <td>{{ $medicine->category ?: '—' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $medicine->review_status)) }}</span></td>
                                <td>
                                    @if($medicine->is_published)
                                        <span class="badge badge-teal">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('admin.medicines.edit', $medicine) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this medicine entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No medicine records found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $medicines->links() }}
            </div>
        </div>
    </div>
</div>

@include('admin.medicines.import')

@endsection
