@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Hospitals</h1>
        <a href="{{ route('admin.hospitals') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.hospitals.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required
                                placeholder="Apollo Hospital">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hospital Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required
                                placeholder="à¤…à¤ªà¥‹à¤²à¥‹ à¤…à¤¸à¥à¤ªà¤¤à¤¾à¤²">
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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control" placeholder="Building / Plot No">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control" placeholder="Street / Area">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Landmark</label>
                            <input type="text" name="landmark" class="form-control" placeholder="Near Metro Station / Mall">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" placeholder="Rajasthan" value="Rajasthan">
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone 1</label>
                            <div class="input-group">
                                <input type="text" name="country_code_1" class="form-control" style="max-width: 80px;" value="+91" placeholder="+91">
                                <input type="text" name="phone_1" class="form-control" required placeholder="11 2345 6789">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone 2</label>
                            <div class="input-group">
                                <input type="text" name="country_code_2" class="form-control" style="max-width: 80px;" placeholder="+91">
                                <input type="text" name="phone_2" class="form-control" placeholder="11 9876 5432">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="is_verified" class="form-check-input" value="1"
                                    id="is_ver_new" checked>
                                <label class="form-check-label fw-semibold" for="is_ver_new">Verified Medical
                                    Center</label>
                            </div>
                        </div>
                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Government Schemes & Cashless Facilities</h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_ayushman" class="form-check-input" value="1" id="ayushman_new">
                                        <label class="form-check-label" for="ayushman_new">Ayushman Card</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_janaadhaar" class="form-check-input" value="1" id="janaadhaar_new">
                                        <label class="form-check-label" for="janaadhaar_new">Jan Aadhaar</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="accepts_cghs" class="form-check-input" value="1" id="cghs_new">
                                        <label class="form-check-label" for="cghs_new">CGHS Govt</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_cashless" class="form-check-input" value="1" id="cashless_new">
                                        <label class="form-check-label" for="cashless_new">Cashless Facility</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label fw-semibold">Empanelled Insurance / Cashless Schemes (Comma separated)</label>
                                    <input type="text" name="cashless_schemes_list" class="form-control" placeholder="Star Health, HDFC Ergo, ICICI Lombard, CGHS">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.hospitals') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Register Hospital</button>
                </div>
            </form>
    </div>
</div>
@endsection



