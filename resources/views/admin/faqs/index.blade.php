@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Frequently Asked Questions (FAQs)</h1>
            <p class="text-muted mb-0">Manage knowledge base questions and answers for patient assistance.</p>
        </div>
        <div>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Add FAQ
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.faqs') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search FAQs by question..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.faqs') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- FAQs Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Question (EN / HI)</th>
                        <th>Answer (EN / HI)</th>
                        <th>Category</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td class="ps-4" style="width: 30%;">
                                <div class="fw-bold text-dark">{{ $faq->getTranslation('question', 'en') }}</div>
                                <div class="text-muted fs-7">{{ $faq->getTranslation('question', 'hi') }}</div>
                            </td>
                            <td style="width: 45%;">
                                <div class="text-dark mb-1 fs-7">{{ Str::limit($faq->getTranslation('answer', 'en'), 100) }}</div>
                                <div class="text-muted fs-7">{{ Str::limit($faq->getTranslation('answer', 'hi'), 100) }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $faq->category }}</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $faq->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.faqs.edit', ['faq' => $faq])
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-circle-question fs-1 mb-3 d-block"></i>
                                No FAQs found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
            {{ $faqs->links() }}
        </div>
    </div>
</div>

@include('admin.faqs.create')

@endsection
