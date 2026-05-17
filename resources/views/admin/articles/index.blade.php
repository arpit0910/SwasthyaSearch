@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Medical Articles Management</h1>
            <p class="text-muted mb-0">Publish and maintain expert health publications and educational guides.</p>
        </div>
        <div>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-plus"></i> Publish New Article
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.articles') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search articles by title..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Article Title (EN / HI)</th>
                        <th>Excerpt (EN / HI)</th>
                        <th>Published Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td class="ps-4" style="width: 30%;">
                                <div class="fw-bold text-dark">{{ $article->getTranslation('title', 'en') }}</div>
                                <div class="text-muted fs-7">{{ $article->getTranslation('title', 'hi') }}</div>
                            </td>
                            <td style="width: 45%;">
                                <div class="text-dark mb-1 fs-7">{{ Str::limit($article->getTranslation('excerpt', 'en'), 100) }}</div>
                                <div class="text-muted fs-7">{{ Str::limit($article->getTranslation('excerpt', 'hi'), 100) }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $article->created_at->format('M d, Y') }}</span></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $article->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.articles.edit', ['article' => $article])
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-book-medical fs-1 mb-3 d-block"></i>
                                No articles found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
            {{ $articles->links() }}
        </div>
    </div>
</div>

@include('admin.articles.create')

@endsection
