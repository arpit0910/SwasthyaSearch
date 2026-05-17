<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $faq->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit FAQ Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" value="{{ $faq->getTranslation('question', 'en') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" value="{{ $faq->getTranslation('question', 'hi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="3" required>{{ $faq->getTranslation('answer', 'en') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="3" required>{{ $faq->getTranslation('answer', 'hi') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ $faq->category }}" required>
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
