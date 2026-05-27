<!-- Edit Modal -->
<div class="modal" id="editModal{{ $doctor->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold">Edit Doctor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-user-doctor me-2"></i>Core & Personal
                        Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control"
                                value="{{ $doctor->first_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ $doctor->last_name }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ $doctor->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $doctor->gender == 'Female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="Other" {{ $doctor->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $doctor->email }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone 1</label>
                            <div class="input-group">
                                <input type="text" name="country_code_1" class="form-control" style="max-width: 80px;"
                                    value="{{ !empty($doctor->country_code_1) ? ('+' . ltrim($doctor->country_code_1, '+')) : '+91' }}" placeholder="+91">
                                <input type="text" name="phone_1" class="form-control" value="{{ $doctor->phone_1 }}"
                                    placeholder="9876543210">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone 2</label>
                            <div class="input-group">
                                <input type="text" name="country_code_2" class="form-control" style="max-width: 80px;"
                                    value="{{ !empty($doctor->country_code_2) ? ('+' . ltrim($doctor->country_code_2, '+')) : '' }}" placeholder="+91">
                                <input type="text" name="phone_2" class="form-control" value="{{ $doctor->phone_2 }}"
                                    placeholder="9988776655">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control"
                                value="{{ $doctor->date_of_birth ? $doctor->date_of_birth->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-stethoscope me-2"></i>Professional &
                        Medical Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Medical Specialties / Departments (Select Multiple)
                                <span class="text-danger">*</span></label>
                            <select class="form-select mb-2" data-department-picker="1" data-hidden-select="departments-edit-modal-hidden-{{ $doctor->id }}" data-pills-container="departments-edit-modal-pills-{{ $doctor->id }}">
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="departments-edit-modal-pills-{{ $doctor->id }}" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <select id="departments-edit-modal-hidden-{{ $doctor->id }}" name="departments[]" multiple class="d-none" required>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $doctor->departments->contains($dept->id) ? 'selected' : '' }}>
                                        {{ $dept->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select from dropdown. Selected departments appear below as removable pills.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Medical Registration No.</label>
                                    @php
                                        $isPlaceholderReg = !empty($doctor->registration_number) && (
                                            str_starts_with($doctor->registration_number, 'REG-') ||
                                            str_starts_with($doctor->registration_number, 'RAJ-MC-') ||
                                            str_starts_with($doctor->registration_number, 'MMC-') ||
                                            str_starts_with($doctor->registration_number, 'DMC-') ||
                                            str_starts_with($doctor->registration_number, 'JOD-') ||
                                            str_starts_with($doctor->registration_number, 'KOT-')
                                        );
                                    @endphp
                                    <input type="text" name="registration_number" class="form-control"
                                        value="{{ $isPlaceholderReg ? '' : $doctor->registration_number }}" placeholder="Not Publicly Listed">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Experience (Years) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="experience_years" class="form-control"
                                        value="{{ $doctor->experience_years }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Consultation Fee (Rs.)</label>
                                    <input type="number" step="0.01" name="consultation_fee" class="form-control"
                                        value="{{ $doctor->consultation_fee }}">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="is_verified" class="form-check-input"
                                            value="1" id="is_ver_doc{{ $doctor->id }}"
                                            {{ $doctor->is_verified ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold"
                                            for="is_ver_doc{{ $doctor->id }}">Verified Professional</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Languages Spoken</label>
                            <input type="text" name="languages_spoken" class="form-control"
                                value="{{ !empty($doctor->languages_spoken) ? implode(', ', $doctor->languages_spoken) : '' }}">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Awards & Recognitions</label>
                            <input type="text" name="awards_recognitions" class="form-control"
                                value="{{ !empty($doctor->awards_recognitions) ? implode(', ', $doctor->awards_recognitions) : '' }}">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Memberships & Fellowships</label>
                            <input type="text" name="membership_fellowships" class="form-control"
                                value="{{ !empty($doctor->membership_fellowships) ? implode(', ', $doctor->membership_fellowships) : '' }}">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-file-lines me-2"></i>Biography &
                        Summaries</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Specialization Summary</label>
                            <input type="text" name="specialization_summary" class="form-control"
                                value="{{ $doctor->specialization_summary }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">About Doctor (English) <span
                                    class="text-danger">*</span></label>
                            <textarea name="about_en" class="form-control" rows="3" required>{{ $doctor->getTranslation('about', 'en') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">About Doctor (Hindi) <span
                                    class="text-danger">*</span></label>
                            <textarea name="about_hi" class="form-control" rows="3" required>{{ $doctor->getTranslation('about', 'hi') }}</textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-map-location-dot me-2"></i>Location &
                        Address Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control"
                                value="{{ $doctor->address_line1 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control"
                                value="{{ $doctor->address_line2 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Landmark</label>
                            <input type="text" name="landmark" class="form-control"
                                value="{{ $doctor->landmark }}" placeholder="Near City Mall / Landmark">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control"
                                value="{{ $doctor->city ?? 'Jaipur' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control"
                                value="{{ $doctor->state ?? 'Rajasthan' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control"
                                value="{{ $doctor->pincode }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control"
                                value="{{ $doctor->latitude }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control"
                                value="{{ $doctor->longitude }}">
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


