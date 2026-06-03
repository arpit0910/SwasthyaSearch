@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Edit Quiz</h1>
        <a href="{{ route('admin.quizzes') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.quizzes._form', ['quiz' => $quiz])
            <div class="card-footer bg-light py-3">
                <a href="{{ route('admin.quizzes') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Quiz</button>
            </div>
        </form>
    </div>
</div>
@endsection
