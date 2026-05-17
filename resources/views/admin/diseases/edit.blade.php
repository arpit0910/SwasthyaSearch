<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $disease->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Disease / Symptom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.diseases.update', $disease) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Name / Symptom (English)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ $disease->getTranslation('name', 'en') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Name / Symptom (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" value="{{ $disease->getTranslation('name', 'hi') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Matched Medical Department</label>
                            <select name="department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $disease->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
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
