@extends('layouts.app')

@section('title', 'Home | Online Market')

@section('content')

<!-- 1. HERO SLIDER -->
<div class="container mb-5">
    @include('partials.hero')
</div>

<div class="container">

    <!-- 2. CATEGORIES (CIRCLES) -->
    <div class="mb-5">
        <div class="row g-4 row-cols-3 row-cols-md-4 row-cols-lg-6 justify-content-center">
            @foreach($categories as $cat)
            <div class="col">
                <a href="{{ route('shop', ['category' => $cat->id]) }}" class="text-decoration-none text-dark">
                    <div class="text-center category-item">
                        <!-- The Circle Container -->
                        <div class="mx-auto mb-2 circle-wrapper shadow-sm border">
                            @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                            @else
                            <!-- Fallback Icon -->
                            <div class="d-flex align-items-center justify-content-center h-100 bg-light text-secondary">
                                <i class="bi bi-grid fs-3"></i>
                            </div>
                            @endif
                        </div>
                        <!-- Category Name -->
                        <span class="d-block small fw-bold text-truncate px-1">{{ $cat->name }}</span>
                    </div>
                </a>
            </div>
            @endforeach
            <div class="col">
                <a href="{{ route('shop') }}" class="text-decoration-none text-dark">
                    <div class="text-center category-item">
                        <div class="mx-auto mb-2 circle-wrapper shadow-sm border d-flex align-items-center justify-content-center bg-light text-truncate">
                            <i class="bi bi-arrow-right-circle fs-2"></i>
                        </div>
                        <span class="d-block small fw-bold text-truncate px-1">Ählisi</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 3. DISCOUNTED PRODUCTS (RED BUTTONS) -->
    @if($discountedProducts->count() > 0)
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h4 class="section-title"><i class="bi bi-percent"></i> Arzanladyşdaky harytlar</h4>
            <a href="{{ route('shop') }}" class="view-all-link">Ählisini gör</a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($discountedProducts as $product)
            <div class="col">
                @include('partials.product_card', ['product' => $product, 'btnColor' => 'danger'])
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 4. MIDDLE BANNER -->
    @if(isset($banners[0]))
    <div class="mb-5 rounded-3 overflow-hidden shadow-sm hover-zoom">
        <a href="{{ $banners[0]->link ?? '#' }}">
            <img src="{{ asset('storage/' . $banners[0]->image) }}" class="w-100" style="max-height: 300px; object-fit: cover;" alt="Banner">
        </a>
    </div>
    @endif

    <!-- 5. NEW ARRIVALS (GREEN BUTTONS) -->
    @if($newArrivals->count() > 0)
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h4 class="section-title"><i class="bi bi-stars"></i> New Arrivals</h4>
            <a href="{{ route('shop', ['sort' => 'newest']) }}" class="view-all-link">
                Ählisini gör <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($newArrivals as $product)
            <div class="col">
                <!-- PASS 'success' (Green) color here -->
                @include('partials.product_card', ['product' => $product, 'btnColor' => 'success'])
            </div>
            @endforeach
        </div>
    </div>
    @endif


</div>

<!-- ============================================== -->
<!-- ADVERTISEMENT MODAL (POPUP) -->
<!-- ============================================== -->
@if(isset($popupAd))
<div class="modal fade" id="adModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Transparent Background for the modal wrapper -->
        <div class="modal-content bg-transparent border-0 shadow-none">

            <!-- Container to hold Image + Button together -->
            <div class="position-relative mx-auto" style="max-width: fit-content;">

                <!-- CUSTOM CLOSE BUTTON (Floating X) -->
                <button type="button" id="closeAdBtn" class="ad-close-btn" disabled>
                    <span id="adTimer" class="fw-bold">15</span>
                </button>

                <!-- Ad Image -->
                <a href="{{ $popupAd->link ?? '#' }}" target="_blank" class="d-block">
                    <!-- Using shadow-lg to make the image pop out -->
                    <img src="{{ asset('storage/' . $popupAd->image) }}" class="img-fluid rounded shadow-lg" style="max-height: 80vh; object-fit: contain;">
                </a>
            </div>

        </div>
    </div>
</div>

<!-- POPUP SCRIPT & STYLES -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adModalEl = document.getElementById('adModal');

        if (adModalEl) {
            // 1. Check LocalStorage (One time per day)
            const today = new Date().toISOString().slice(0, 9); // YYYY-MM-DD
            const storageKey = 'seen_home_ad_' + today;

            // If NOT seen today, show modal
            if (!localStorage.getItem(storageKey)) {

                const adModal = new bootstrap.Modal(adModalEl);
                adModal.show();

                // 2. Countdown Logic
                let timeLeft = 10; // Seconds to wait
                const btn = document.getElementById('closeAdBtn');
                const timerSpan = document.getElementById('adTimer');

                const countdown = setInterval(() => {
                    timeLeft--;

                    // Update number
                    if (timerSpan) timerSpan.innerText = timeLeft;

                    // When time is up
                    if (timeLeft <= 0) {
                        clearInterval(countdown);

                        if (btn) {
                            // Enable Button
                            btn.disabled = false;

                            // Add 'ready' class for Orange CSS styling
                            btn.classList.add('ready');

                            // Change text number to X Icon
                            btn.innerHTML = '<i class="bi bi-x-lg"></i>';

                            // Add Click Event to Close
                            btn.addEventListener('click', () => {
                                adModal.hide();
                                // Mark as seen for today
                                localStorage.setItem(storageKey, 'true');
                            });
                        }
                    }
                }, 1000);
            }
        }
    });
</script>

<style>
    /* CSS FOR THE FLOATING X BUTTON */
    .ad-close-btn {
        position: absolute;
        top: -15px;
        right: -15px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 2px solid #fff;
        z-index: 1056;
        /* Above the image */
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        outline: none;
        padding: 0;
    }

    /* State 1: WAITING (Gray Background) */
    .ad-close-btn:disabled {
        background-color: #6c757d;
        color: #fff;
        cursor: not-allowed;
        font-size: 1.1rem;
    }

    /* State 2: READY (Orange Background) */
    .ad-close-btn.ready {
        background-color: rgba(255, 68, 0, 0.64);
        /* Orange-Red color */
        color: white;
        cursor: pointer;
        transform: scale(1.1);
        font-size: 1.3rem;
    }

    .ad-close-btn.ready:hover {
        background-color: rgba(255, 68, 0, 0.29);
        transform: scale(1.2);
    }
</style>
@endif


<!-- EXISTING PAGE STYLES -->
<style>
    /* Category Circle Styles */
    .circle-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        transition: transform 0.3s ease, border-color 0.3s ease;
        background-color: #fff;
    }

    .circle-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .category-item:hover .circle-wrapper {
        transform: translateY(-5px);
        border-color: #405FA5 !important;
        /* Theme Gold Color */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .category-item:hover span {
        color: #405FA5;
        transition: color 0.3s;
    }

    /* Mobile Adjustment */
    @media (max-width: 768px) {
        .circle-wrapper {
            width: 70px;
            height: 70px;
        }
    }

    /* Product Card Styles */
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .product-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: transparent;
    }

    /* 1. NEW BADGE (Green Pill) */
    .badge-new {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        background-color: #28a745;
        /* Bootstrap Success Green */
        color: white;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 800;
        border-radius: 20px;
        /* Pill shape */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* 2. DISCOUNT BADGE (Red Circle) */
    .badge-discount {
        position: absolute;
        left: 10px;
        z-index: 10;
        width: 50px;
        height: 50px;
        background-color: #dc3545;
        /* Bootstrap Danger Red */
        color: white;
        border-radius: 50%;
        /* Perfect Circle */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        /* Animation pop */
        animation: popIn 0.3s ease-out;
    }

    .badge-discount .percent {
        font-size: 0.9rem;
        font-weight: 900;
        line-height: 1;
    }

    .badge-discount .text {
        font-size: 0.5rem;
        text-transform: uppercase;
        font-weight: bold;
        opacity: 0.9;
    }

    @keyframes popIn {
        0% {
            transform: scale(0);
        }

        80% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Container for badges to keep them organized */
    .product-badge-container {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 5px;
        /* Spaces them out perfectly */
    }

    .badge-new,
    .badge-discount {
        position: relative;
        /* Changed from absolute */
        top: auto;
        left: auto;
    }
</style>

@endsection