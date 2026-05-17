<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Department Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department Name (English)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ $department->getTranslation('name', 'en') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" value="{{ $department->getTranslation('name', 'hi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description (English)</label>
                            <textarea name="description_en" class="form-control" rows="3" required>{{ $department->getTranslation('description', 'en') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description (Hindi)</label>
                            <textarea name="description_hi" class="form-control" rows="3" required>{{ $department->getTranslation('description', 'hi') }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" id="is_act{{ $department->id }}" {{ $department->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_act{{ $department->id }}">Active Specialty</label>
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
