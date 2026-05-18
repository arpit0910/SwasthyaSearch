<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Import Blood Banks CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.blood_banks.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select CSV File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control form-control-lg" accept=".csv,.txt"
                            required>
                        <div class="form-text mt-2">
                            Please upload a valid CSV file with headers matching the export format (e.g., name_en,
                            name_hi, city, state, pincode, address_en, address_hi, country_code, phone,
                            emergency_country_code, emergency_phone, email, website, is_verified, is_24_7,
                            is_government, component_facility, apheresis_facility, available_blood_groups, latitude,
                            longitude).
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i
                            class="fa-solid fa-cloud-arrow-up me-2"></i>Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
