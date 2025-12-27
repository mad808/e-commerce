@extends('admin.layouts.app')

@section('title', 'Täze Habar Goş')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.news.index') }}">Habarlar</a>
                    </li>
                    <li class="breadcrumb-item active">Täze Habar</li>
                </ol>
            </nav>
            <h2 class="fw-bold">
                <i class="bi bi-plus-circle"></i> Täze Habar Goş
            </h2>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong><i class="bi bi-exclamation-triangle"></i> Ýalňyşlyklar:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.news.store') }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Ady <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                required
                                maxlength="255">
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Summary -->
                        <div class="mb-4">
                            <label for="summary" class="form-label fw-bold">
                                Gysgaça Mazmun
                            </label>
                            <textarea class="form-control @error('summary') is-invalid @enderror"
                                id="summary"
                                name="summary"
                                rows="3"
                                maxlength="500">{{ old('summary') }}</textarea>
                            <small class="text-muted">Iň köp 500 simwol</small>
                            @error('summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div class="mb-4">
                            <label for="body" class="form-label fw-bold">
                                Doly Mazmun <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('body') is-invalid @enderror"
                                id="body"
                                name="body"
                                rows="10"
                                required>{{ old('body') }}</textarea>
                            @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                Surat <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                class="form-control @error('image') is-invalid @enderror"
                                id="image"
                                name="image"
                                accept="image/*"
                                onchange="previewImage(event)"
                                required>
                            <small class="text-muted">Format: JPG, PNG, WEBP. Iň köp: 2MB</small>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="video" class="form-label fw-bold">Wideo (MP4)</label>
                            <input type="file"
                                class="form-control @error('video') is-invalid @enderror"
                                id="video"
                                name="video"
                                accept="video/mp4,video/x-m4v,video/*">
                            <small class="text-muted">Format: MP4, MOV.</small>
                            @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <!-- FIX: Added value="1" -->
                                <input class="form-check-input"
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Aktiw
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg"></i> Ýatda Sakla
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary px-4">
                                <i class="bi bi-x-lg"></i> Ýatyr
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection