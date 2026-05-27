<!-- Create Modal -->
<div class="modal" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Publish New Medical Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.articles.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (English)</label>
                            <input type="text" name="title_en" class="form-control" required placeholder="Understanding Cardiovascular Health">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (Hindi)</label>
                            <input type="text" name="title_hi" class="form-control" required placeholder="हृदय स्वास्थ्य को समझना">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (English)</label>
                            <textarea name="excerpt_en" class="form-control" rows="2" required placeholder="A comprehensive guide to keeping your heart healthy..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (Hindi)</label>
                            <textarea name="excerpt_hi" class="form-control" rows="2" required placeholder="अपने दिल को स्वस्थ रखने के लिए एक व्यापक गाइड..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (English - Markdown Supported)</label>
                            <textarea name="content_en" class="form-control font-monospace fs-7" rows="10" required placeholder="## Introduction&#10;&#10;Cardiovascular diseases..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (Hindi - Markdown Supported)</label>
                            <textarea name="content_hi" class="form-control font-monospace fs-7" rows="10" required placeholder="## परिचय&#10;&#10;हृदय रोग..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Publish Article</button>
                </div>
            </form>
        </div>
    </div>
</div>


