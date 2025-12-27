@extends('admin.layouts.app')

@section('title', 'Haryt maglumaty')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none">Harytlar</a></li>
                    <li class="breadcrumb-item active">#{{ $product->id }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold text-dark">{{ $product->name }}</h3>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary px-4 shadow-sm me-2" style="border-radius: 10px;">
                <i class="bi bi-pencil-square me-2"></i> Redaktirlemek
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-2"></i> Yza gaýt
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-sticky" style="top: 20px;">
                <div class="card-body p-0">
                    <div class="bg-black d-flex align-items-center justify-content-center position-relative" style="height: 480px; cursor: crosshair;" id="mainImageContainer">
                        @if($product->image)
                        <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid w-100 h-100" style="object-fit: contain;">
                        @else
                        <i class="bi bi-image text-white-50 display-1"></i>
                        @endif

                        @if($product->video_url)
                        <div id="videoPlayer" class="d-none w-100 h-100 bg-black">
                            <video id="productVideo" class="w-100 h-100" style="object-fit: contain;">
                                <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                            </video>

                            <div class="custom-video-controls shadow-lg">
                                <button id="playPauseBtn" class="btn btn-primary btn-sm rounded-circle shadow">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                                <input type="range" id="progressBar" value="0" min="0" max="100" class="flex-grow-1">
                                <span id="timeDisplay" class="text-white small font-monospace">0:00 / 0:00</span>
                                <button id="muteBtn" class="btn btn-sm text-white">
                                    <i class="bi bi-volume-up-fill"></i>
                                </button>
                                <input type="range" id="volumeBar" value="100" min="0" max="100" style="width: 50px;">
                            </div>
                        </div>
                        @endif

                        <div class="position-absolute top-0 end-0 m-3 z-2">
                            <span class="badge bg-dark bg-opacity-50 blur-card px-3 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-camera me-1"></i> {{ $product->images->count() + 1 }} Media
                            </span>
                        </div>
                    </div>

                    <div class="p-3 bg-white border-top">
                        <div class="d-flex gap-2 overflow-auto thumbnail-container py-1">
                            @if($product->image)
                            <div class="thumb-wrapper active" data-image="{{ asset('storage/' . $product->image) }}" data-type="image">
                                <img src="{{ asset('storage/' . $product->image) }}" class="rounded shadow-sm">
                            </div>
                            @endif

                            @foreach($product->images as $img)
                            <div class="thumb-wrapper" data-image="{{ asset('storage/' . $img->image) }}" data-type="image">
                                <img src="{{ asset('storage/' . $img->image) }}" class="rounded shadow-sm">
                            </div>
                            @endforeach

                            @if($product->video_url)
                            <div class="thumb-wrapper video-thumb" data-type="video">
                                <div class="bg-dark rounded shadow-sm d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-play-btn-fill text-danger fs-4"></i>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-xl-5">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }} rounded-pill px-3">
                            {{ $product->is_active ? 'Aktiw' : 'Draft' }}
                        </span>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-eye-fill me-1"></i> {{ $product->views }} Gezek görlen
                        </div>
                    </div>

                    <h1 class="fw-bold text-dark mb-2">{{ $product->name }}</h1>

                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 fs-6">
                            {{ $product->category->name }}
                        </span>

                        @if($product->barcode)
                        <div class="copy-barcode-btn bg-light border px-3 py-1 rounded-3 small text-muted font-monospace d-flex align-items-center cursor-pointer"
                            data-clipboard-text="{{ $product->barcode }}">
                            <i class="bi bi-upc-scan me-2 icon-default"></i>
                            <i class="bi bi-check-circle-fill text-success me-2 icon-success d-none"></i>
                            <span class="barcode-text">{{ $product->barcode }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <div class="price-box bg-white border shadow-sm">
                                <div class="label">Satuw bahasy</div>
                                <div class="value text-primary">{{ number_format($product->price, 2) }} <small>TMT</small></div>
                                @if($product->old_price)
                                <div class="text-muted text-decoration-line-through small">{{ number_format($product->old_price, 2) }} TMT</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="price-box bg-danger bg-opacity-10 border-danger border-opacity-25">
                                <div class="label text-danger">Gelýän bahasy</div>
                                <div class="value text-danger">{{ number_format($product->cost_price, 2) }} <small>TMT</small></div>
                                <div class="small text-danger-emphasis">Çykdajy</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="price-box bg-success bg-opacity-10 border-success border-opacity-25">
                                <div class="label text-success">Arassa peýda</div>
                                <div class="value text-success">+{{ number_format($product->price - $product->cost_price, 2) }} <small>TMT</small></div>
                                <div class="small text-success-emphasis">Girdeji</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Skladda bar:</h6>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            @php $stockPercent = min(($product->stock / 100) * 100, 100); @endphp
                            <div class="progress-bar bg-{{ $product->stock > 10 ? 'success' : 'warning' }}" style="width: {{ $stockPercent }}%"></div>
                        </div>
                        <div class="mt-2 fw-bold {{ $product->stock > 0 ? 'text-dark' : 'text-danger' }}">
                            {{ $product->stock }} sany bar
                        </div>
                    </div>

                    @if($product->attributes->isNotEmpty())
                    <div class="mb-5">
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 tracking-wider">Aýratynlyklar</h6>
                        <div class="row g-2">
                            @foreach($product->attributes as $attr)
                            <div class="col-6 col-md-4">
                                <div class="p-2 border rounded-3 bg-light bg-opacity-50 h-100">
                                    <div class="text-muted small">{{ $attr->name }}</div>
                                    <div class="fw-bold text-dark">{{ $attr->pivot->value }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($product->admin_note)
                    <div class="p-4 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-25 mb-5 shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-lock-fill text-warning me-2 fs-5"></i>
                            <h6 class="mb-0 fw-bold text-warning-emphasis">Admin Bellikleri (Şahsy)</h6>
                        </div>
                        <p class="mb-0 text-dark opacity-75">{{ $product->admin_note }}</p>
                    </div>
                    @endif

                    <div class="mb-5">
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 tracking-wider">Haryt barada</h6>
                        <div class="text-muted lh-lg" style="white-space: pre-line;">
                            {{ $product->description ?? 'Düşündiriş ýok.' }}
                        </div>
                    </div>

                    <div class="pt-4 border-top mt-auto d-flex flex-wrap gap-4 text-muted small">
                        <div><i class="bi bi-clock me-1"></i> Goşulan wagty: <b>{{ $product->created_at->format('d.m.Y H:i') }}</b></div>
                        <div><i class="bi bi-arrow-repeat me-1"></i> Soňky üýtgeşme: <b>{{ $product->updated_at->diffForHumans() }}</b></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mediaLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 d-flex align-items-center justify-content-center">
                <img id="lightboxImage" src="" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .tracking-wider {
        letter-spacing: 0.05em;
    }

    .blur-card {
        backdrop-filter: blur(8px);
    }

    .price-box {
        padding: 1.5rem 1rem;
        border-radius: 18px;
        text-align: center;
        transition: transform 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .price-box:hover {
        transform: translateY(-5px);
    }

    .price-box .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .price-box .value {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
    }

    .thumb-wrapper {
        width: 80px;
        height: 80px;
        min-width: 80px;
        border: 2px solid transparent;
        padding: 3px;
        border-radius: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        opacity: 0.7;
    }

    .thumb-wrapper.active {
        border-color: #0d6efd;
    }

    .thumb-wrapper.active img {
        opacity: 1;
    }

    .thumb-wrapper:hover img {
        opacity: 1;
    }

    /* Custom Video Controls styling */
    .custom-video-controls {
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10;
    }

    /* Scrollbar Styling */
    .thumbnail-container::-webkit-scrollbar {
        height: 5px;
    }

    .thumbnail-container::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const thumbWrappers = document.querySelectorAll('.thumb-wrapper');
        const mainImage = document.getElementById('mainImage');
        const videoPlayer = document.getElementById('videoPlayer');
        const video = document.getElementById('productVideo');
        const mainImageContainer = document.getElementById('mainImageContainer');
        const lightboxImage = document.getElementById('lightboxImage');

        // Toggle Video/Image View
        thumbWrappers.forEach(thumb => {
            thumb.addEventListener('click', function() {
                thumbWrappers.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                if (this.getAttribute('data-type') === 'video') {
                    mainImage.classList.add('d-none');
                    videoPlayer.classList.remove('d-none');
                } else {
                    mainImage.classList.remove('d-none');
                    videoPlayer.classList.add('d-none');
                    mainImage.src = this.getAttribute('data-image');
                    if (video) video.pause();
                }
            });
        });

        // Zoom/Lightbox
        mainImageContainer.addEventListener('click', function() {
            if (!mainImage.classList.contains('d-none')) {
                lightboxImage.src = mainImage.src;
                const modal = new bootstrap.Modal(document.getElementById('mediaLightbox'));
                modal.show();
            }
        });

        // Video Logic
        const playPauseBtn = document.getElementById('playPauseBtn');
        if (video && playPauseBtn) {
            playPauseBtn.onclick = (e) => {
                e.stopPropagation();
                if (video.paused) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i>';
                }
            };

            video.addEventListener('timeupdate', () => {
                const progress = (video.currentTime / video.duration) * 100;
                document.getElementById('progressBar').value = progress;
            });
        }

        // Copy Barcode
        const copyBtn = document.querySelector('.copy-barcode-btn');
        if (copyBtn) {
            copyBtn.onclick = function() {
                const text = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(text).then(() => {
                    const icon = this.querySelector('.icon-default');
                    const success = this.querySelector('.icon-success');
                    icon.classList.add('d-none');
                    success.classList.remove('d-none');
                    setTimeout(() => {
                        icon.classList.remove('d-none');
                        success.classList.add('d-none');
                    }, 2000);
                });
            };
        }
    });
</script>
@endsection