@php
// 1. Check if product is new (last 7 days)
$isNew = $product->created_at->diffInDays(now()) < 7;

    // 2. Updated discount logic: Check if discount_percent is set and greater than 0
    $hasDiscount=$product->discount_percent && $product->discount_percent > 0;
    @endphp

    <div class="card h-100 product-card-pro border-0 shadow-sm bg-white overflow-hidden">
        <div class="image-container-pro position-relative">

            <div class="badge-stack position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between align-items-start" style="z-index: 5;">
                <div class="d-flex flex-column gap-2">
                    @if($isNew)
                    <span class="glass-pill badge-new">TÄZE</span>
                    @endif

                    @if($hasDiscount)
                    {{-- Using the percentage directly from your database column --}}
                    <span class="glass-pill badge-sale">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                <div class="wishlist-float-btn">
                    <livewire:toggle-favorite :product-id="$product->id" :key="'fav-'.$product->id" />
                </div>
            </div>

            <a href="{{ route('product.show', $product->slug) }}" class="d-block h-100">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                    class="card-img-top-pro"
                    alt="{{ $product->name }}">
                @else
                <div class="image-placeholder d-flex align-items-center justify-content-center h-100 bg-light">
                    <i class="bi bi-camera fs-1 text-secondary opacity-25"></i>
                </div>
                @endif
            </a>
        </div>

        <div class="card-body p-3 d-flex flex-column">
            <div class="mb-1">
                <small class="category-tag text-muted text-uppercase fw-bold">{{ $product->category->name ?? 'Haryt' }}</small>
            </div>

            <h6 class="product-title-pro mb-3">
                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark fw-bold">
                    {{ $product->name }}
                </a>
            </h6>

            @if($product->barcode)
            <div class="barcode-wrapper copy-barcode-btn mb-3" data-clipboard-text="{{ $product->barcode }}" title="Click to copy">
                <div class="barcode-pill">
                    <i class="bi bi-upc-scan icon-default"></i>
                    <i class="bi bi-check-circle-fill text-success icon-success d-none"></i>
                    <span class="barcode-text">{{ $product->barcode }}</span>
                </div>
            </div>
            @endif

            <div class="mt-auto pt-3 border-top border-light">
                <div class="d-flex align-items-center mb-3">
                    <div class="price-box">
                        @if($hasDiscount)
                        {{-- Show calculated sale_price as the main price --}}
                        <span class="current-price fw-black fs-4 text-dark">
                            {{ number_format($product->sale_price, 2) }} <small class="fs-6">m.</small>
                        </span>
                        {{-- Show original price (price) with a strike-through --}}
                        <span class="old-price-pro text-muted text-decoration-line-through ms-2 small">
                            {{ number_format($product->price, 2) }} m.
                        </span>
                        @else
                        {{-- Just show the normal price --}}
                        <span class="current-price fw-black fs-4 text-dark">
                            {{ number_format($product->price, 2) }} <small class="fs-6">m.</small>
                        </span>
                        @endif
                    </div>
                </div>

                <livewire:add-to-cart-button :product-id="$product->id" :btn-color="$btnColor ?? 'success'" />
            </div>
        </div>
    </div>

    <style>
        /* Card Layout */
        .product-card-pro {
            border-radius: 20px !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }

        .product-card-pro:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
        }

        /* Image Dynamics */
        .image-container-pro {
            height: 240px;
            overflow: hidden;
            background: #fdfdfd;
            border-bottom: 1px solid #f8f9fa;
        }

        .card-img-top-pro {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        /* Typography */
        .category-tag {
            font-size: 0.65rem;
            letter-spacing: 0.5px;
            opacity: 0.7;
        }

        .product-title-pro a {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        .product-title-pro a:hover {
            color: #285179 !important;
        }

        /* Barcode Pill */
        .barcode-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            padding: 4px 12px;
            border-radius: 10px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 0.75rem;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .barcode-pill:hover {
            background: #eef1f4;
            border-color: #adb5bd;
            color: #333;
        }

        /* Price Styling */
        .fw-black {
            font-weight: 900;
        }

        .current-price {
            line-height: 1;
        }

        .old-price-pro {
            opacity: 0.5;
            font-weight: 600;
        }

        /* Floating Wishlist Button Interaction */
        .wishlist-float-btn {
            transform: scale(0.85) translateX(10px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-card-pro:hover .wishlist-float-btn {
            transform: scale(1) translateX(0);
            opacity: 1;
        }
    </style>

    <script>
        if (typeof barcodeInitialized === 'undefined') {
            document.addEventListener('DOMContentLoaded', function() {
                const barcodeElements = document.querySelectorAll('.copy-barcode-btn');
                barcodeElements.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const barcode = this.getAttribute('data-clipboard-text');
                        const defaultIcon = this.querySelector('.icon-default');
                        const successIcon = this.querySelector('.icon-success');
                        const textSpan = this.querySelector('.barcode-text');
                        const originalText = textSpan.innerText;

                        navigator.clipboard.writeText(barcode).then(() => {
                            defaultIcon.classList.add('d-none');
                            successIcon.classList.remove('d-none');
                            textSpan.classList.add('text-success');
                            textSpan.innerText = "Copied!";

                            setTimeout(() => {
                                defaultIcon.classList.remove('d-none');
                                successIcon.classList.add('d-none');
                                textSpan.classList.remove('text-success');
                                textSpan.innerText = originalText;
                            }, 1500);
                        });
                    });
                });
            });
            var barcodeInitialized = true;
        }
    </script>