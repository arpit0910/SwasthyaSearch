<!-- Edit Modal -->
<div class="modal" id="editModal{{ $hospital->id }}" tabindex="-1">
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
                            <input type="text" name="name_en" class="form-control"
                                value="{{ $hospital->getTranslation('name', 'en') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control"
                                value="{{ $hospital->getTranslation('name', 'hi') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facility Type</label>
                            <select name="type" class="form-select" required>
                                <option value="Hospital" {{ $hospital->type == 'Hospital' ? 'selected' : '' }}>Hospital
                                </option>
                                <option value="Clinic" {{ $hospital->type == 'Clinic' ? 'selected' : '' }}>Clinic
                                </option>
                                <option value="Specialty Center"
                                    {{ $hospital->type == 'Specialty Center' ? 'selected' : '' }}>Specialty Center
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" value="{{ $hospital->city }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control"
                                value="{{ $hospital->address_line1 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control"
                                value="{{ $hospital->address_line2 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Landmark</label>
                            <input type="text" name="landmark" class="form-control"
                                value="{{ $hospital->landmark }}" placeholder="Near Metro Station / Mall">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control"
                                value="{{ $hospital->state ?? 'Rajasthan' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" value="{{ $hospital->pincode }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control"
                                value="{{ $hospital->latitude }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control"
                                value="{{ $hospital->longitude }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone 1</label>
                            <div class="input-group">
                                <input type="text" name="country_code_1" class="form-control" style="max-width: 80px;" value="{{ !empty($hospital->country_code_1) ? ('+' . ltrim($hospital->country_code_1, '+')) : '+91' }}" placeholder="+91">
                                <input type="text" name="phone_1" class="form-control" value="{{ $hospital->phone_1 }}" required placeholder="11 2345 6789">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone 2</label>
                            <div class="input-group">
                                <input type="text" name="country_code_2" class="form-control" style="max-width: 80px;" value="{{ !empty($hospital->country_code_2) ? ('+' . ltrim($hospital->country_code_2, '+')) : '' }}" placeholder="+91">
                                <input type="text" name="phone_2" class="form-control" value="{{ $hospital->phone_2 }}" placeholder="11 9876 5432">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="is_verified" class="form-check-input" value="1"
                                    id="is_ver{{ $hospital->id }}" {{ $hospital->is_verified ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_ver{{ $hospital->id }}">Verified
                                    Medical Center</label>
                            </div>
                        </div>
                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Government Schemes & Cashless Facilities</h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_ayushman" class="form-check-input"
                                            value="1" id="ayushman_{{ $hospital->id }}"
                                            {{ $hospital->accepts_ayushman ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ayushman_{{ $hospital->id }}">Ayushman
                                            Card</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_janaadhaar" class="form-check-input"
                                            value="1" id="janaadhaar_{{ $hospital->id }}"
                                            {{ $hospital->accepts_janaadhaar ? 'checked' : '' }}>
                                        <label class="form-check-label" for="janaadhaar_{{ $hospital->id }}">Jan
                                            Aadhaar</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_cghs" class="form-check-input"
                                            value="1" id="cghs_{{ $hospital->id }}"
                                            {{ $hospital->accepts_cghs ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cghs_{{ $hospital->id }}">CGHS
                                            Govt</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_cashless" class="form-check-input"
                                            value="1" id="cashless_{{ $hospital->id }}"
                                            {{ $hospital->is_cashless ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cashless_{{ $hospital->id }}">Cashless
                                            Facility</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label fw-semibold">Empanelled Insurance / Cashless Schemes
                                        (Comma separated)</label>
                                    <input type="text" name="cashless_schemes_list" class="form-control"
                                        value="{{ is_array($hospital->cashless_schemes_list) ? implode(', ', $hospital->cashless_schemes_list) : $hospital->cashless_schemes_list }}">
                                </div>
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


