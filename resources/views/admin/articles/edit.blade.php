<!-- Edit Modal -->
<div class="modal" id="editModal{{ $article->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Medical Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.articles.update', $article) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                Keep both English and Hindi content on the same article record so the site can switch languages without creating separate entries.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (English)</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $article->getTranslation('title', 'en')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (Hindi)</label>
                            <input type="text" name="title_hi" class="form-control" value="{{ old('title_hi', $article->getTranslation('title', 'hi')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (English)</label>
                            <textarea name="excerpt_en" class="form-control" rows="2" required>{{ old('excerpt_en', $article->getTranslation('excerpt', 'en')) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (Hindi)</label>
                            <textarea name="excerpt_hi" class="form-control" rows="2" required>{{ old('excerpt_hi', $article->getTranslation('excerpt', 'hi')) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (English - Markdown Supported)</label>
                            <textarea name="content_en" class="form-control font-monospace fs-7" rows="10" required>{{ old('content_en', $article->getTranslation('content', 'en')) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (Hindi - Markdown Supported)</label>
                            <textarea name="content_hi" class="form-control font-monospace fs-7" rows="10" required>{{ old('content_hi', $article->getTranslation('content', 'hi')) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $article->category) }}" placeholder="General Medicine">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Author Name</label>
                            <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $article->author_name) }}" placeholder="Swasthya Editorial">
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="modal_edit_is_published_{{ $article->id }}" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="modal_edit_is_published_{{ $article->id }}">Published</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
