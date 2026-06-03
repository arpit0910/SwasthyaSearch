@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Medicine</h1>
        <a href="{{ route('admin.medicines') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.medicines.store') }}" method="POST">
            @csrf
            @include('admin.medicines._form')
            <div class="card-footer bg-light py-3">
                <a href="{{ route('admin.medicines') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Medicine</button>
            </div>
        </form>
    </div>
</div>
@endsection
