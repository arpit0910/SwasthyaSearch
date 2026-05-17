@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Diseases / Symptoms Mapping</h1>
            <p class="text-muted mb-0">Manage symptoms and diseases mapped to medical specialties for AI matching.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i> Import CSV
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add Disease / Symptom
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.diseases') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search diseases or symptoms..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.diseases') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Diseases Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Symptom / Disease (EN / HI)</th>
                        <th>Matched Department</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($diseases as $disease)
                        <tr>
                            <td class="ps-4" style="width: 50%;">
                                <div class="fw-bold text-dark">{{ $disease->getTranslation('name', 'en') }}</div>
                                <div class="text-muted fs-7">{{ $disease->getTranslation('name', 'hi') }}</div>
                            </td>
                            <td><span class="badge badge-teal fs-7 border">{{ $disease->department->getTranslation('name', 'en') ?? 'General' }}</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $disease->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.diseases.destroy', $disease) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this disease/symptom?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.diseases.edit', ['disease' => $disease])
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-tag fs-1 mb-3 d-block"></i>
                                No diseases or symptoms found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
            {{ $diseases->links() }}
        </div>
    </div>
</div>

@include('admin.diseases.create')
@include('admin.diseases.import')

@endsection
