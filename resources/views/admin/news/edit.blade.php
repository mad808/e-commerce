@extends('admin.layouts.app')

@section('title', 'Habary Üýtget')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.news.index') }}">Habarlar</a>
                    </li>
                    <li class="breadcrumb-item active">Üýtget</li>
                </ol>
            </nav>
            <h2 class="fw-bold">
                <i class="bi bi-pencil-square"></i> Habary Üýtget
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
                    <form action="{{ route('admin.news.update', $news) }}"
                        method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Ady <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title', $news->title) }}"
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
                                maxlength="500">{{ old('summary', $news->summary) }}</textarea>
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
                                required>{{ old('body', $news->body) }}</textarea>
                            @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                Surat
                            </label>

                            @if($news->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $news->image) }}"
                                    alt="Current Image"
                                    class="img-thumbnail"
                                    style="max-width: 300px;">
                                <p class="text-muted small mt-1">Häzirki surat</p>
                            </div>
                            @endif

                            <input type="file"
                                class="form-control @error('image') is-invalid @enderror"
                                id="image"
                                name="image"
                                accept="image/*"
                                onchange="previewImage(event)">
                            <small class="text-muted">Format: JPG, PNG, WEBP. Iň köp: 2MB. Täze surat saýlamak islemeýän bolsaňyz boş goýuň.</small>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                                <p class="text-muted small mt-1">Täze surat</p>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="video" class="form-label fw-bold">Wideo (MP4)</label>

                            @if($news->video_url)
                            <div class="mb-2">
                                <video width="200" controls class="rounded shadow-sm">
                                    <source src="{{ asset('storage/' . $news->video_url) }}" type="video/mp4">
                                </video>
                                <p class="text-muted small">Häzirki wideo</p>
                            </div>
                            @endif

                            <input type="file"
                                class="form-control @error('video') is-invalid @enderror"
                                id="video"
                                name="video"
                                accept="video/mp4,video/x-m4v,video/*">
                            <small class="text-muted">Täze wideo goşmak isleseňiz saýlaň (MP4). Boş goýsaňyz könesi galar.</small>
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
                                    {{ old('is_active', $news->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Aktiw
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg"></i> Täzele
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