@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Edit Blood Banks</h1>
        <a href="{{ route('admin.blood_banks') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.blood_banks.update', $bloodBank) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Bank Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required value="{{ $bloodBank->getTranslation('name', 'en') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Blood Bank Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required value="{{ $bloodBank->getTranslation('name', 'hi') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" required value="{{ $bloodBank->city }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" value="{{ $bloodBank->state }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address (English)</label>
                            <input type="text" name="address_en" class="form-control" required value="{{ $bloodBank->getTranslation('address', 'en') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Complete Address (Hindi)</label>
                            <input type="text" name="address_hi" class="form-control" required value="{{ $bloodBank->getTranslation('address', 'hi') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" value="{{ $bloodBank->pincode }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Landmark</label>
                            <input type="text" name="landmark" class="form-control" value="{{ $bloodBank->landmark }}" placeholder="Near Main Gate / Circle">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control" value="{{ $bloodBank->latitude }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control" value="{{ $bloodBank->longitude }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">General Helpline</label>
                            <div class="input-group">
                                <input type="text" name="country_code" class="form-control" style="max-width: 80px;" value="{{ !empty($bloodBank->country_code) ? ('+' . ltrim($bloodBank->country_code, '+')) : '+91' }}">
                                <input type="text" name="phone" class="form-control" value="{{ $bloodBank->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Emergency Helpline</label>
                            <div class="input-group">
                                <input type="text" name="emergency_country_code" class="form-control" style="max-width: 80px;" value="{{ !empty($bloodBank->emergency_country_code) ? ('+' . ltrim($bloodBank->emergency_country_code, '+')) : '+91' }}">
                                <input type="text" name="emergency_phone" class="form-control" required value="{{ $bloodBank->emergency_phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $bloodBank->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Website</label>
                            <input type="text" name="website" class="form-control" value="{{ $bloodBank->website }}">
                        </div>

                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Facility Metadata & Status</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_government" class="form-check-input" value="1" id="is_govt_{{ $bloodBank->id }}" {{ $bloodBank->is_government ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_govt_{{ $bloodBank->id }}">Government Facility</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_24_7" class="form-check-input" value="1" id="is_247_{{ $bloodBank->id }}" {{ $bloodBank->is_24_7 ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_247_{{ $bloodBank->id }}">24/7 Availability</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_verified" class="form-check-input" value="1" id="is_ver_{{ $bloodBank->id }}" {{ $bloodBank->is_verified ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_ver_{{ $bloodBank->id }}">Verified Blood Bank</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="component_facility" class="form-check-input" value="1" id="comp_fac_{{ $bloodBank->id }}" {{ $bloodBank->component_facility ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="comp_fac_{{ $bloodBank->id }}">Blood Component Separation Facility</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="apheresis_facility" class="form-check-input" value="1" id="aph_fac_{{ $bloodBank->id }}" {{ $bloodBank->apheresis_facility ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="aph_fac_{{ $bloodBank->id }}">Apheresis (Platelet) Facility</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-3">Available Blood Groups</h6>
                            @php
                                $currentBGs = is_array($bloodBank->available_blood_groups) ? $bloodBank->available_blood_groups : [];
                            @endphp
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                    <div class="form-check">
                                        <input type="checkbox" name="available_blood_groups[]" class="form-check-input" value="{{ $bg }}" id="bg_{{ $bloodBank->id }}_{{ $bg }}" {{ in_array($bg, $currentBGs) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="bg_{{ $bloodBank->id }}_{{ $bg }}">{{ $bg }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.blood_banks') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
    </div>
</div>
@endsection



