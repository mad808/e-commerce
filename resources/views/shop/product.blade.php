@extends('layouts.app')

@section('title', $product->name)

@section('content')

<!-- CUSTOM CSS -->
<style>
    /* --- GALLERY STYLES --- */
    .thumbnail-scroll {
        max-height: 550px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #405FA5 #f1f1f1;
    }

    .thumbnail-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .thumbnail-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .thumbnail-scroll::-webkit-scrollbar-thumb {
        background: #405FA5;
        border-radius: 10px;
    }

    .thumb-card {
        border: 2px solid transparent;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s ease;
        margin-bottom: 10px;
        position: relative;
    }

    .thumb-card img {
        width: 100%;
        height: 90px;
        object-fit: cover;
        display: block;
    }

    .thumb-card:hover {
        opacity: 0.5;
        transform: scale(1);
    }

    .thumb-card.active {
        border-color: #405FA5;
        opacity: 1;
        box-shadow: 0 2px 8px rgba(64, 95, 165, 0.3);
    }

    .main-image-area {
        height: 550px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        border: 1px solid #eee;
        border-radius: 8px;
        position: relative;
        cursor: zoom-in;
        overflow: hidden;
    }

    .main-image-area img {
        transition: transform 0.3s ease;
    }

    .main-image-area:hover img {
        transform: scale(1.02);
    }

    @media (max-width: 991px) {
        .thumbnail-scroll {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            overflow-y: hidden;
            max-height: none;
            margin-top: 15px;
            padding-bottom: 5px;
        }

        .thumb-card {
            width: 80px;
            min-width: 80px;
            flex-shrink: 0;
            margin-bottom: 0;
            margin-right: 10px;
        }

        .main-image-area {
            height: 350px;
        }
    }

    /* --- RIGHT SIDE SPECIFIC STYLES --- */
    .store-header {
        background-color: #000;
        color: #fff;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .btn-enter {
        background: #fff;
        color: #000;
        border-radius: 20px;
        padding: 5px 20px;
        font-weight: bold;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-enter:hover {
        background: #f0f0f0;
        transform: scale(1.05);
    }

    .product-title {
        font-size: 1.6rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .product-price {
        font-size: 1.8rem;
        font-weight: 600;
        color: #000;
        margin-bottom: 15px;
    }

    .meta-strip {
        background-color: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .desc-text {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 5px;
    }

    .desc-link {
        color: #35699eff;
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: block;
    }

    .desc-link:hover {
        text-decoration: underline;
    }

    /* MAIN ADD TO CART BUTTON (Wide Beige) */
    .btn-beige-cart {
        background-color: #35699eff !important;
        color: white !important;
        width: 100%;
        height: 50px;
        border-radius: 25px !important;
        font-size: 1.2rem;
        transition: 0.3s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-beige-cart:hover {
        background-color: #2a5280 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(53, 105, 158, 0.3);
    }

    /* RELATED GRID BADGE FIXES */
    .related-grid-item .badge-new {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 20;
        background-color: #198754 !important;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .related-grid-item .badge-discount {
        position: absolute;
        top: 45px;
        left: 10px;
        z-index: 20;
        width: 50px;
        height: 50px;
        background-color: #ff2a00 !important;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 5px rgba(0, 0, 0, 0.2);
    }

    .related-grid-item .badge-discount .percent {
        font-size: 0.9rem;
        line-height: 1;
    }

    .related-grid-item .badge-discount .text {
        font-size: 0.5rem;
        text-transform: uppercase;
    }

    /* Ensure card has relative positioning for badges */
    .related-grid-item .card {
        position: relative !important;
        overflow: hidden;
    }

    /* Lightbox improvements */
    .lightbox-image {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
    }

    /* 1. Hide the actual radio circle */
    .attribute-radio input {
        display: none;
    }

    /* 2. The Box Style (Unselected) */
    .option-box {
        display: inline-block;
        min-width: 40px;
        height: 35px;
        padding: 0 15px;
        border: 1px solid #e6e6e6;
        /* Grey border */
        border-radius: 8px;
        /* Rounded corners */
        background: #fff;
        color: #333;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 33px;
        /* Vertically center text */
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    /* 3. Hover Effect */
    .option-box:hover {
        border-color: #285179;
        /* Brand Blue on Hover */
    }

    /* 4. SELECTED State (The Orange Border) */
    .attribute-radio input:checked+.option-box {
        border: 2px solid #285179;
        /* Orange Border */
        color: #333;
        font-weight: 700;
        box-shadow: 0 0 0 1px rgba(253, 126, 20, 0.1);
    }

    /* 5. Disabled State (Optional) */
    .attribute-radio input:disabled+.option-box {
        background-color: #f9f9f9;
        color: #ccc;
        border-color: #eee;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    /* Add this to your <style> section */
    #progressBar::-webkit-slider-thumb {
        appearance: none;
        width: 14px;
        height: 14px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    #progressBar::-moz-range-thumb {
        width: 14px;
        height: 14px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        border: none;
    }

    #volumeBar::-webkit-slider-thumb {
        appearance: none;
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
    }

    #volumeBar::-moz-range-thumb {
        width: 12px;
        height: 12px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        border: none;
    }

    /* --- PRO PRODUCT CARD STYLES --- */
    .product-card-pro {
        border-radius: 20px !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        background: #fff;
    }

    .product-card-pro:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
    }

    .image-container-pro {
        height: 220px;
        /* Slightly shorter for the related grid */
        overflow: hidden;
        background: #fdfdfd;
        border-bottom: 1px solid #f8f9fa;
        position: relative;
    }

    .card-img-top-pro {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.7s ease;
    }

    .product-card-pro:hover .card-img-top-pro {
        transform: scale(1.08);
    }

    /* Glassmorphism Badges */
    .glass-pill {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        display: inline-block;
    }

    .badge-new {
        color: #198754;
    }

    .badge-sale {
        color: #dc3545;
    }

    /* Wishlist Button Fade-in */
    .wishlist-float-btn {
        transform: scale(0.85) translateX(10px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .product-card-pro:hover .wishlist-float-btn {
        transform: scale(1) translateX(0);
        opacity: 1;
    }

    .product-title-pro a {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.95rem;
    }

    .fw-black {
        font-weight: 900;
    }
</style>

<div class="bg-light min-vh-100 pb-5">

    <!-- 1. BREADCRUMB -->
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small text-muted">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-muted">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop', ['category' => $product->category_id]) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active text-dark">{{ Str::limit($product->name, 40) }}</li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <!-- 2. MAIN PRODUCT CARD -->
        <div class="bg-white rounded-4 shadow-sm overflow-hidden mb-5">
            <div class="row g-0">

                <!-- === LEFT: GALLERY === -->
                <div class="col-lg-7 p-4 border-end">
                    <div class="row g-3">
                        <div class="col-lg-2 order-2 order-lg-1">
                            <div class="thumbnail-scroll">
                                <div class="thumb-card active" data-image="{{ asset('storage/' . $product->image) }}" data-index="0">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Thumbnail 0">
                                </div>
                                @foreach($product->images as $key => $img)
                                <div class="thumb-card" data-image="{{ asset('storage/' . $img->image) }}" data-index="{{ $key + 1 }}">
                                    <img src="{{ asset('storage/' . $img->image) }}" alt="Thumbnail {{ $key + 1 }}">
                                </div>
                                @endforeach

                                <!-- VIDEO THUMBNAIL -->
                                @if($product->video_url)
                                <div class="thumb-card video-thumb" data-type="video">
                                    <div style="position: relative; width: 100%; height: 90px; background: #000; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-play-circle-fill text-white" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-10 order-1 order-lg-2">
                            <div class="main-image-area position-relative" id="mainImageContainer">
                                <!-- VIDEO PLAYER (HIDDEN BY DEFAULT) -->
                                @if($product->video_url)
                                <div id="videoPlayer" class="d-none" style="width: 100%; height: 100%; position: relative;">
                                    <video id="productVideo" class="w-100 h-100" style="object-fit: contain; background: #000;">
                                        <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>

                                    <!-- Custom Controls Overlay -->
                                    <div class="custom-video-controls" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); padding: 20px; display: flex; align-items: center; gap: 15px;">
                                        <!-- Play/Pause Button -->
                                        <button id="playPauseBtn" class="btn btn-light rounded-circle" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border: none;">
                                            <i class="bi bi-play-fill" style="font-size: 1.3rem;"></i>
                                        </button>

                                        <!-- Progress Bar -->
                                        <div style="flex: 1; display: flex; align-items: center; gap: 10px;">
                                            <input type="range" id="progressBar" value="0" min="0" max="100" style="flex: 1; cursor: pointer; height: 6px; border-radius: 10px; background: rgba(255,255,255,0.3); outline: none;">
                                            <span id="timeDisplay" class="text-white small" style="min-width: 80px; font-weight: 500;">0:00 / 0:00</span>
                                        </div>

                                        <!-- Volume Control -->
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <button id="muteBtn" class="btn btn-sm text-white" style="background: transparent; border: none; font-size: 1.2rem;">
                                                <i class="bi bi-volume-up-fill"></i>
                                            </button>
                                            <input type="range" id="volumeBar" value="100" min="0" max="100" style="width: 80px; cursor: pointer; height: 4px;">
                                        </div>

                                        <!-- Fullscreen Button -->
                                        <button id="fullscreenBtn" class="btn btn-sm text-white" style="background: transparent; border: none; font-size: 1.2rem;">
                                            <i class="bi bi-arrows-fullscreen"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif

                                <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid w-100 h-100 object-fit-contain" alt="{{ $product->name }}">

                                <div class="position-absolute top-0 end-0 m-3">
                                    <livewire:toggle-favorite :product-id="$product->id" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- === RIGHT: INFO === -->
                <div class="col-lg-5 p-4 bg-white d-flex flex-column">

                    <!-- Title -->

                    <h1 class="product-title">{{ $product->name }}</h1>

                    <!-- Price -->
                    <div class="product-price d-flex align-items-center flex-wrap gap-2">
                        @if($product->discount_percent > 0)
                        <span class="text-dark fw-bold">
                            {{ number_format($product->sale_price, 2) }} m
                        </span>

                        <small class="text-muted text-decoration-line-through fs-6">
                            {{ number_format($product->price, 2) }} m
                        </small>

                        <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem;">
                            -{{ $product->discount_percent }}%
                        </span>
                        @else
                        <span class="text-dark fw-bold">
                            {{ number_format($product->price, 2) }} m
                        </span>
                        @endif
                    </div>

                    <!-- Category -->
                    <div class="mb-1">
                        <div class="meta-strip">
                            <i class="bi bi-tag-fill text-muted me-2"></i>
                            <span class="fw-bold text-dark">{{ $product->category->name }}</span>
                        </div>
                    </div>

                    @if($product->barcode)
                    <div class="text-muted small font-monospace mb-1 copy-barcode-btn"
                        data-clipboard-text="{{ $product->barcode }}"
                        title="Click to copy"
                        style="cursor: pointer; transition: 0.2s;">

                        <i class="bi bi-upc-scan me-1 icon-default"></i>
                        <i class="bi bi-check-circle-fill me-1 text-success icon-success d-none"></i>

                        <span class="barcode-text">{{ $product->barcode }}</span>
                    </div>
                    @endif

                    <!-- ATTRIBUTES SECTION -->
                    @if($product->attributes->isNotEmpty())
                    <div class="mb-4">
                        @foreach($product->attributes as $attr)
                        <div class="mb-3">
                            <!-- Attribute Name -->
                            <h6 class="fw-bold text-dark small mb-2" style="font-size: 0.85rem;">
                                {{ $attr->name }}: <span class="text-muted fw-normal">{{ $attr->pivot->value }}</span>
                            </h6>

                            <!-- Options Container -->
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                $values = explode(',', $attr->pivot->value);
                                @endphp

                                @foreach($values as $val)
                                <label class="attribute-radio">
                                    <!-- Hidden Radio Input -->
                                    <input type="radio" name="attr_{{ $attr->id }}" value="{{ trim($val) }}" {{ $loop->first ? 'checked' : '' }}>

                                    <!-- Visual Box -->
                                    <span class="option-box">
                                        {{ trim($val) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <!-- End -->

                    <div class="d-flex align-items-center gap-3 mb-2 text-muted small">
                        <span>
                            <i class="bi bi-eye-fill"></i> {{ number_format($product->views) }} Gorulen sany
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">

                        @if($product->stock > 0)
                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> In Stock</span>
                        @else
                        <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>
                        @endif

                        <div class="desc-text my-3">
                            <i class="bi bi-info-circle text-dark"></i> {{ $product->description ?? 'No description available for this item.' }}
                        </div>
                    </div>

                    <!-- Livewire Add to Cart -->
                    <div class="mt-auto pt-4 border-top">

                        <livewire:add-to-cart-button
                            :product-id="$product->id"
                            :with-quantity="true" />

                    </div>

                </div>
            </div>
        </div>


        <!-- Banner -->
        @if(isset($banners[1]))
        <div class="mb-5 rounded-3 overflow-hidden shadow-sm hover-zoom">
            <a href="{{ $banners[1]->link ?? '#' }}">
                <img src="{{ asset('storage/' . $banners[1]->image) }}" class="w-100" style="max-height: 300px; object-fit: cover;" alt="Banner">
            </a>
        </div>
        @endif

        <!-- 3. RELATED PRODUCTS (GRID LAYOUT) -->
        @if($relatedProducts->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h3 class="fw-bold text-dark m-0">Degişli önümler</h3>
                <a href="{{ route('shop', ['category' => $product->category_id]) }}" class="text-dark fw-bold text-decoration-none small">
                    Hemmesini gör <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                @foreach($relatedProducts as $rel)
                <div class="col">
                    <x-product-card :product="$rel" btnColor="success" />
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<!-- LIGHTBOX MODAL -->
<div class="modal fade" id="productLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content bg-black">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center h-100">
                <img id="lightboxImage" src="" class="lightbox-image" alt="Product Image">
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all thumbnail cards
        const thumbCards = document.querySelectorAll('.thumb-card');
        const mainImage = document.getElementById('mainImage');
        const videoPlayer = document.getElementById('videoPlayer');
        const mainImageContainer = document.getElementById('mainImageContainer');
        const lightboxImage = document.getElementById('lightboxImage');
        let lightboxModal = null;

        // Function to show image and hide video
        function showImage(imageUrl) {
            if (mainImage && imageUrl) {
                mainImage.src = imageUrl;
                mainImage.classList.remove('d-none');
            }
            if (videoPlayer) {
                videoPlayer.classList.add('d-none');
                const video = videoPlayer.querySelector('video');
                if (video) video.pause();
            }
        }

        // Function to show video and hide image
        function showVideo() {
            if (videoPlayer) {
                videoPlayer.classList.remove('d-none');
            }
            if (mainImage) {
                mainImage.classList.add('d-none');
            }
        }

        // Function to update both main and lightbox images
        function updateImages(imageUrl) {
            showImage(imageUrl);

            // Update lightbox image if modal is open
            if (lightboxImage && lightboxModal && document.getElementById('productLightbox').classList.contains('show')) {
                lightboxImage.src = imageUrl;
            }
        }

        // Add click event to each thumbnail
        thumbCards.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbCards.forEach(t => t.classList.remove('active'));

                // Add active class to clicked thumbnail
                this.classList.add('active');

                // Check if it's a video thumbnail
                if (this.getAttribute('data-type') === 'video') {
                    showVideo();
                } else {
                    // Get the image URL from data attribute
                    const imageUrl = this.getAttribute('data-image');
                    updateImages(imageUrl);
                }
            });
        });

        // Lightbox functionality (only for images)
        if (mainImageContainer) {
            mainImageContainer.addEventListener('click', function() {
                // Only open lightbox if image is visible
                if (mainImage && !mainImage.classList.contains('d-none')) {
                    const currentImageSrc = mainImage.src;
                    if (lightboxImage && currentImageSrc) {
                        lightboxImage.src = currentImageSrc;

                        // Initialize and show Bootstrap modal
                        lightboxModal = new bootstrap.Modal(document.getElementById('productLightbox'));
                        lightboxModal.show();
                    }
                }
            });
        }

        // Update lightbox image when modal is shown
        const modalElement = document.getElementById('productLightbox');
        if (modalElement) {
            modalElement.addEventListener('shown.bs.modal', function() {
                if (!mainImage.classList.contains('d-none')) {
                    lightboxImage.src = mainImage.src;
                }
            });
        }

        // Optional: Keyboard navigation for thumbnails
        document.addEventListener('keydown', function(e) {
            const activeThumb = document.querySelector('.thumb-card.active');
            if (!activeThumb) return;

            let nextThumb;
            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                nextThumb = activeThumb.nextElementSibling;
            } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                nextThumb = activeThumb.previousElementSibling;
            }

            if (nextThumb && nextThumb.classList.contains('thumb-card')) {
                nextThumb.click();
                nextThumb.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        });
    });

    // Barcode copy functionality
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeElements = document.querySelectorAll('.copy-barcode-btn');

        barcodeElements.forEach(btn => {
            btn.addEventListener('click', function() {
                const barcode = this.getAttribute('data-clipboard-text');
                const defaultIcon = this.querySelector('.icon-default');
                const successIcon = this.querySelector('.icon-success');
                const textSpan = this.querySelector('.barcode-text');
                const originalText = textSpan.innerText;

                // Copy to Clipboard
                navigator.clipboard.writeText(barcode).then(() => {
                    // Visual Feedback (Show Check Icon)
                    defaultIcon.classList.add('d-none');
                    successIcon.classList.remove('d-none');
                    textSpan.classList.add('text-success');
                    textSpan.innerText = "Copied!";

                    // Revert back after 1.5 seconds
                    setTimeout(() => {
                        defaultIcon.classList.remove('d-none');
                        successIcon.classList.add('d-none');
                        textSpan.classList.remove('text-success');
                        textSpan.innerText = originalText;
                    }, 1500);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });
        });
    });



    // Custom Video Controls
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('productVideo');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const progressBar = document.getElementById('progressBar');
        const timeDisplay = document.getElementById('timeDisplay');
        const muteBtn = document.getElementById('muteBtn');
        const volumeBar = document.getElementById('volumeBar');
        const fullscreenBtn = document.getElementById('fullscreenBtn');

        if (!video) return;

        // Play/Pause
        playPauseBtn?.addEventListener('click', function() {
            if (video.paused) {
                video.play();
                this.innerHTML = '<i class="bi bi-pause-fill" style="font-size: 1.3rem;"></i>';
            } else {
                video.pause();
                this.innerHTML = '<i class="bi bi-play-fill" style="font-size: 1.3rem;"></i>';
            }
        });

        // Update progress bar
        video.addEventListener('timeupdate', function() {
            const progress = (video.currentTime / video.duration) * 100;
            progressBar.value = progress;

            // Update time display
            const current = formatTime(video.currentTime);
            const total = formatTime(video.duration);
            timeDisplay.textContent = `${current} / ${total}`;
        });

        // Seek video
        progressBar?.addEventListener('input', function() {
            const time = (this.value / 100) * video.duration;
            video.currentTime = time;
        });

        // Volume control
        volumeBar?.addEventListener('input', function() {
            video.volume = this.value / 100;
            updateVolumeIcon();
        });

        // Mute/Unmute
        muteBtn?.addEventListener('click', function() {
            video.muted = !video.muted;
            updateVolumeIcon();
        });

        function updateVolumeIcon() {
            if (video.muted || video.volume === 0) {
                muteBtn.innerHTML = '<i class="bi bi-volume-mute-fill"></i>';
            } else if (video.volume < 0.5) {
                muteBtn.innerHTML = '<i class="bi bi-volume-down-fill"></i>';
            } else {
                muteBtn.innerHTML = '<i class="bi bi-volume-up-fill"></i>';
            }
        }

        // Fullscreen
        fullscreenBtn?.addEventListener('click', function() {
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitRequestFullscreen) {
                video.webkitRequestFullscreen();
            }
        });

        // Format time helper
        function formatTime(seconds) {
            if (isNaN(seconds)) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        // Space bar to play/pause
        document.addEventListener('keydown', function(e) {
            if (e.code === 'Space' && !video.classList.contains('d-none')) {
                e.preventDefault();
                playPauseBtn.click();
            }
        });
    });
</script>

@endsection