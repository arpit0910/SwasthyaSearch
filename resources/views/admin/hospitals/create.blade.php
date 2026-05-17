<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Register New Hospital</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required
                                placeholder="Apollo Hospital">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required
                                placeholder="अपोलो अस्पताल">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facility Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Hospital">Hospital</option>
                                <option value="Clinic">Clinic</option>
                                <option value="Specialty Center">Specialty Center</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" required placeholder="Delhi">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address</label>
                            <input type="text" name="address" class="form-control" required
                                placeholder="123 Healthcare Blvd, Sector 4">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Emergency Helpline</label>
                            <input type="text" name="emergency_phone" class="form-control" required
                                placeholder="+91 11 2345 6789">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="is_verified" class="form-check-input" value="1"
                                    id="is_ver_new" checked>
                                <label class="form-check-label fw-semibold" for="is_ver_new">Verified Medical
                                    Center</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Register Hospital</button>
                </div>
            </form>
        </div>
    </div>
</div>
