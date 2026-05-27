@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Create Articles</h1>
        <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary">Back</a>
    </div>
    <div class="card">
        <form action="{{ route('admin.articles.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (English)</label>
                            <input type="text" name="title_en" class="form-control" required placeholder="Understanding Cardiovascular Health">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Article Title (Hindi)</label>
                            <input type="text" name="title_hi" class="form-control" required placeholder="हृदय स्वास्थ्य को समझना">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (English)</label>
                            <textarea name="excerpt_en" class="form-control" rows="2" required placeholder="A comprehensive guide to keeping your heart healthy..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Excerpt (Hindi)</label>
                            <textarea name="excerpt_hi" class="form-control" rows="2" required placeholder="अपने दिल को स्वस्थ रखने के लिए एक व्यापक गाइड..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (English - Markdown Supported)</label>
                            <textarea name="content_en" class="form-control font-monospace fs-7" rows="10" required placeholder="## Introduction&#10;&#10;Cardiovascular diseases..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Content (Hindi - Markdown Supported)</label>
                            <textarea name="content_hi" class="form-control font-monospace fs-7" rows="10" required placeholder="## परिचय&#10;&#10;हृदय रोग..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <a href="{{ route('admin.articles') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Publish Article</button>
                </div>
            </form>
    </div>
</div>
@endsection




