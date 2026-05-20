<div class="modal fade" id="editModal{{ $question->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Cached Medical Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.cached_medical_questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (English)</label>
                            <input type="text" name="question_en" class="form-control" value="{{ $question->question_en }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Question (Hindi)</label>
                            <input type="text" name="question_hi" class="form-control" value="{{ $question->question_hi }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (English)</label>
                            <textarea name="answer_en" class="form-control" rows="4" required>{{ $question->answer_en }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Answer (Hindi)</label>
                            <textarea name="answer_hi" class="form-control" rows="4" required>{{ $question->answer_hi }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (English)</label>
                            <textarea name="detailed_answer_en" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle">{{ $question->detailed_answer_en }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Detailed Answer (Hindi)</label>
                            <textarea name="detailed_answer_hi" class="form-control" rows="4" placeholder="Optional expanded answer for chatbot details toggle">{{ $question->detailed_answer_hi }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ $question->category }}" required>
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
