@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Doctors</h1>
        <a href="{{ route('admin.doctors') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.doctors.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-user-doctor me-2"></i>Core & Personal Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required placeholder="Ramesh">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required placeholder="Kumar">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="doctor@example.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone 1</label>
                            <div class="input-group">
                                <input type="text" name="country_code_1" class="form-control" style="max-width: 80px;" value="+91" placeholder="+91">
                                <input type="text" name="phone_1" class="form-control" placeholder="9876543210">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone 2</label>
                            <div class="input-group">
                                <input type="text" name="country_code_2" class="form-control" style="max-width: 80px;" placeholder="+91">
                                <input type="text" name="phone_2" class="form-control" placeholder="9988776655">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-stethoscope me-2"></i>Professional & Medical Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Medical Specialties / Departments (Select Multiple) <span class="text-danger">*</span></label>
                            <select class="form-select mb-2" data-department-picker="1" data-hidden-select="departments-create-hidden" data-pills-container="departments-create-pills">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->getTranslation('name', 'en') }}</option>
                                @endforeach
                            </select>
                            <div id="departments-create-pills" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <select id="departments-create-hidden" name="departments[]" multiple class="d-none" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->getTranslation('name', 'en') }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Select from dropdown. Selected departments appear below as removable pills.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Medical Registration No.</label>
                                    <input type="text" name="registration_number" class="form-control" placeholder="Not Publicly Listed">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Experience (Years) <span class="text-danger">*</span></label>
                                    <input type="number" name="experience_years" class="form-control" required placeholder="15">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Consultation Fee (Rs.)</label>
                                    <input type="number" step="0.01" name="consultation_fee" class="form-control" placeholder="1000.00">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="is_verified" class="form-check-input" value="1" id="is_ver_doc_new" checked>
                                        <label class="form-check-label fw-semibold" for="is_ver_doc_new">Verified Professional</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Languages Spoken</label>
                            <input type="text" name="languages_spoken" class="form-control" placeholder="English, Hindi, Punjabi">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Awards & Recognitions</label>
                            <input type="text" name="awards_recognitions" class="form-control" placeholder="Best Doctor Award 2023">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Memberships & Fellowships</label>
                            <input type="text" name="membership_fellowships" class="form-control" placeholder="Fellow of IMA, Member of AIIMS">
                            <div class="form-text">Comma-separated values</div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-file-lines me-2"></i>Biography & Summaries</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Specialization Summary</label>
                            <input type="text" name="specialization_summary" class="form-control" placeholder="Advanced laparoscopic surgery and general consulting">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">About Doctor (English) <span class="text-danger">*</span></label>
                            <textarea name="about_en" class="form-control" rows="3" required placeholder="Expert senior consultant..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">About Doctor (Hindi) <span class="text-danger">*</span></label>
                            <textarea name="about_hi" class="form-control" rows="3" required placeholder="वरिष्ठ सलाहकार..."></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-map-location-dot me-2"></i>Location & Address Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control" placeholder="Clinic / Chamber No">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control" placeholder="Street / Area">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Landmark</label>
                            <input type="text" name="landmark" class="form-control" placeholder="Near City Mall / Landmark">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" placeholder="Jaipur" value="Jaipur">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" placeholder="Rajasthan" value="Rajasthan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="302001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control" placeholder="26.9124">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control" placeholder="75.7873">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.doctors') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Register Doctor</button>
                </div>
            </form>
    </div>
</div>
@endsection




