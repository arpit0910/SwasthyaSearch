<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $doctor->id }}" tabindex="-1">
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
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ $doctor->phone }}">
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
                            <select name="departments[]" multiple class="form-select" style="height: 120px;" required>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ $doctor->departments->contains($dept->id) ? 'selected' : '' }}>
                                        {{ $dept->getTranslation('name', 'en') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple departments.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Medical Registration No. <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="registration_number" class="form-control"
                                        value="{{ $doctor->registration_number }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Experience (Years) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="experience_years" class="form-control"
                                        value="{{ $doctor->experience_years }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Consultation Fee (₹)</label>
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
                    <div class="row g-3">
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
                </div>
                <div class="modal-footer border-0 bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
