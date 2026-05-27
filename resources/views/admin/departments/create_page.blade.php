@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Departments</h1>
        <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.departments.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department Name (English)</label>
                            <input type="text" name="name_en" class="form-control" required placeholder="Cardiology">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Department Name (Hindi)</label>
                            <input type="text" name="name_hi" class="form-control" required placeholder="à¤¹à¥ƒà¤¦à¤¯ à¤°à¥‹à¤— à¤µà¤¿à¤­à¤¾à¤—">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description (English)</label>
                            <textarea name="description_en" class="form-control" rows="3" required placeholder="Deals with disorders of the heart..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Description (Hindi)</label>
                            <textarea name="description_hi" class="form-control" rows="3" required placeholder="à¤¹à¥ƒà¤¦à¤¯ à¤¸à¥‡ à¤¸à¤‚à¤¬à¤‚à¤§à¤¿à¤¤ à¤°à¥‹à¤—à¥‹à¤‚ à¤•à¤¾ à¤‰à¤ªà¤šà¤¾à¤°..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" value="1" id="is_act_new" checked>
                                <label class="form-check-label fw-semibold" for="is_act_new">Active Specialty</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Department</button>
                </div>
            </form>
    </div>
</div>
@endsection



