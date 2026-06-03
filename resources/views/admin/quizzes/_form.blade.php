@php
$quiz = $quiz ?? null;
$questionsPayload = old('questions_payload', $quiz ? json_encode($quiz->questions_json ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : "[]");
$resultsPayload = old('results_payload', $quiz ? json_encode($quiz->result_ranges_json ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : "[]");
@endphp

<div class="card-body p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Title (English)</label>
            <input type="text" name="title_en" class="form-control" required value="{{ old('title_en', $quiz->title_en ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Title (Hindi)</label>
            <input type="text" name="title_hi" class="form-control" required value="{{ old('title_hi', $quiz->title_hi ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $quiz->slug ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $quiz->category ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Description (English)</label>
            <textarea name="description_en" class="form-control" rows="2">{{ old('description_en', $quiz->description_en ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Description (Hindi)</label>
            <textarea name="description_hi" class="form-control" rows="2">{{ old('description_hi', $quiz->description_hi ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Intro (English)</label>
            <textarea name="intro_en" class="form-control" rows="3">{{ old('intro_en', $quiz->intro_en ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Intro (Hindi)</label>
            <textarea name="intro_hi" class="form-control" rows="3">{{ old('intro_hi', $quiz->intro_hi ?? '') }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Questions JSON</label>
            <textarea name="questions_payload" class="form-control font-monospace" rows="12">{{ $questionsPayload }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold">Result Ranges JSON</label>
            <textarea name="results_payload" class="form-control font-monospace" rows="8">{{ $resultsPayload }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Disclaimer (English)</label>
            <textarea name="disclaimer_en" class="form-control" rows="2">{{ old('disclaimer_en', $quiz->disclaimer_en ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Disclaimer (Hindi)</label>
            <textarea name="disclaimer_hi" class="form-control" rows="2">{{ old('disclaimer_hi', $quiz->disclaimer_hi ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Title (English)</label>
            <input type="text" name="meta_title_en" class="form-control" value="{{ old('meta_title_en', $quiz->meta_title_en ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Title (Hindi)</label>
            <input type="text" name="meta_title_hi" class="form-control" value="{{ old('meta_title_hi', $quiz->meta_title_hi ?? '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Description (English)</label>
            <textarea name="meta_description_en" class="form-control" rows="2">{{ old('meta_description_en', $quiz->meta_description_en ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Meta Description (Hindi)</label>
            <textarea name="meta_description_hi" class="form-control" rows="2">{{ old('meta_description_hi', $quiz->meta_description_hi ?? '') }}</textarea>
        </div>
        <div class="col-12">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="quiz_published" @checked(old('is_published', $quiz->is_published ?? false))>
                <label class="form-check-label" for="quiz_published">Published</label>
            </div>
        </div>
    </div>
</div>
