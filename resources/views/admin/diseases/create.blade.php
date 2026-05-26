<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Add New Disease / Symptom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.diseases.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Disease Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required placeholder="Chest Pain">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Disease Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required placeholder="छाती में दर्द">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Symptoms (English)</label>
                            <textarea name="symptoms_en" class="form-control" rows="2" placeholder="chest pain, shortness of breath, sweating"></textarea>
                            <small class="text-muted">Use comma, semicolon, or new line to separate symptoms.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Symptoms (Hindi)</label>
                            <textarea name="symptoms_hi" class="form-control" rows="2" placeholder="छाती में दर्द, सांस फूलना, पसीना आना"></textarea>
                            <small class="text-muted">Order should match English symptoms if provided.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Matched Medical Department</label>
                            <select name="department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->getTranslation('name', 'en') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>
