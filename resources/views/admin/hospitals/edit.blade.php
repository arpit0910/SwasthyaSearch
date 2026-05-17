<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $hospital->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Hospital Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.update', $hospital) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (English)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ $hospital->getTranslation('name', 'en') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" value="{{ $hospital->getTranslation('name', 'hi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facility Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Hospital" {{ $hospital->type == 'Hospital' ? 'selected' : '' }}>Hospital</option>
                                <option value="Clinic" {{ $hospital->type == 'Clinic' ? 'selected' : '' }}>Clinic</option>
                                <option value="Specialty Center" {{ $hospital->type == 'Specialty Center' ? 'selected' : '' }}>Specialty Center</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ $hospital->city }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $hospital->address }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Emergency Helpline</label>
                            <input type="text" name="emergency_phone" class="form-control" value="{{ $hospital->emergency_phone }}" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="is_verified" class="form-check-input" value="1" id="is_ver{{ $hospital->id }}" {{ $hospital->is_verified ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_ver{{ $hospital->id }}">Verified Medical Center</label>
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
