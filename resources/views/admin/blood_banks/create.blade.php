<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Register New Blood Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.blood_banks.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Bank Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required placeholder="SMS Hospital Blood Bank">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Bank Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required placeholder="एसएमएस अस्पताल ब्लड बैंक">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" required placeholder="Jaipur" value="Jaipur">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" placeholder="Rajasthan" value="Rajasthan">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address (English)</label>
                            <input type="text" name="address_en" class="form-control" required placeholder="JLN Marg, Jaipur">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address (Hindi)</label>
                            <input type="text" name="address_hi" class="form-control" required placeholder="जेएलएन मार्ग, जयपुर">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="302004">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control" placeholder="26.8924">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control" placeholder="75.8173">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">General Helpline</label>
                            <div class="input-group">
                                <input type="text" name="country_code" class="form-control" style="max-width: 80px;" value="+91" placeholder="+91">
                                <input type="text" name="phone" class="form-control" placeholder="141 256 0291">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Emergency Helpline</label>
                            <div class="input-group">
                                <input type="text" name="emergency_country_code" class="form-control" style="max-width: 80px;" value="+91" placeholder="+91">
                                <input type="text" name="emergency_phone" class="form-control" required placeholder="141 256 0292">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="contact@bloodbank.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Website</label>
                            <input type="text" name="website" class="form-control" placeholder="https://bloodbank.com">
                        </div>

                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Facility Metadata & Status</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_government" class="form-check-input" value="1" id="is_govt_new">
                                        <label class="form-check-label fw-semibold" for="is_govt_new">Government Facility</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_24_7" class="form-check-input" value="1" id="is_247_new" checked>
                                        <label class="form-check-label fw-semibold" for="is_247_new">24/7 Availability</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_verified" class="form-check-input" value="1" id="is_ver_new" checked>
                                        <label class="form-check-label fw-semibold" for="is_ver_new">Verified Blood Bank</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="component_facility" class="form-check-input" value="1" id="comp_fac_new" checked>
                                        <label class="form-check-label fw-semibold" for="comp_fac_new">Blood Component Separation Facility</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="apheresis_facility" class="form-check-input" value="1" id="aph_fac_new">
                                        <label class="form-check-label fw-semibold" for="aph_fac_new">Apheresis (Platelet) Facility</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Available Blood Groups</h6>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                    <div class="form-check">
                                        <input type="checkbox" name="available_blood_groups[]" class="form-check-input" value="{{ $bg }}" id="bg_new_{{ $bg }}" checked>
                                        <label class="form-check-label fw-semibold" for="bg_new_{{ $bg }}">{{ $bg }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Register Blood Bank</button>
                </div>
            </form>
        </div>
    </div>
</div>
