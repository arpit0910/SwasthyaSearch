<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Add Cached Medical Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.cached_medical_questions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (English)</label>
                            <textarea name="detailed_answer_en" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (Hindi)</label>
                            <textarea name="detailed_answer_hi" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" required placeholder="General Medical / Cardiology / Neurology">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Question</button>
                </div>
            </form>
        </div>
    </div>
</div>
