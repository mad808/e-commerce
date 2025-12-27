@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-black mb-1">
                <i class="bi bi-heart-fill text-danger me-2"></i>My Wishlist
            </h2>
            <p class="text-muted mb-0">Manage your saved items and find recommendations.</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                {{ $favorites->count() }} Items Saved
            </span>
        </div>
    </div>

    @if($favorites->count() > 0)
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($favorites as $product)
            @php $isNew = $product->created_at->diffInDays(now()) < 7; @endphp
            <div class="col">
                <div class="card h-100 product-card border-0 shadow-sm bg-white">
                    <div class="position-relative overflow-hidden m-2 rounded-4 bg-light" style="height: 220px;">
                        <div class="position-absolute top-0 start-0 w-100 p-2 d-flex justify-content-between align-items-start" style="z-index: 5;">
                            @if($isNew)
                                <span class="badge-glass badge-new-pro">TÄZE</span>
                            @else
                                <span></span>
                            @endif
                            
                            @if($product->old_price && $product->old_price > $product->price)
                                @php $percent = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                                <div class="badge-glass badge-discount-pro">
                                    <span class="fw-bold">-{{ $percent }}%</span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="w-100 h-100 product-img-main" 
                                 style="object-fit: cover;" 
                                 alt="{{ $product->name }}">
                        </a>

                        <div class="position-absolute bottom-0 end-0 m-2 wishlist-float">
                            <livewire:toggle-favorite :product-id="$product->id" :key="'fav-'.$product->id" />
                        </div>
                    </div>

                    <div class="card-body p-3 pt-2 d-flex flex-column">
                        <small class="text-uppercase text-muted fw-bold opacity-75" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                            {{ $product->category->name ?? 'Product' }}
                        </small>
                        
                        <h6 class="mb-2">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-dark fw-bold text-decoration-none product-link">
                                {{ $product->name }}
                            </a>
                        </h6>

                        @if($product->barcode)
                        <div class="barcode-pill copy-barcode-btn mb-3" data-clipboard-text="{{ $product->barcode }}">
                            <i class="bi bi-upc-scan icon-default"></i>
                            <i class="bi bi-check2 text-success icon-success d-none"></i>
                            <span class="barcode-text">{{ $product->barcode }}</span>
                        </div>
                        @endif

                        <div class="mt-auto">
                            <div class="d-flex align-items-end gap-2 mb-3">
                                <span class="fs-5 fw-black text-dark">{{ number_format($product->price, 2) }} <small class="fs-6">m.</small></span>
                                @if($product->old_price)
                                    <span class="text-muted text-decoration-line-through small pb-1" style="font-size: 0.8rem; opacity: 0.6;">
                                        {{ number_format($product->old_price, 2) }} m.
                                    </span>
                                @endif
                            </div>
                            <livewire:add-to-cart-button :product-id="$product->id" :btn-color="'success'" />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $favorites->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-heart-break fs-1 text-danger"></i>
        </div>
        <h3 class="fw-bold mb-2">Your wishlist is empty</h3>
        <p class="text-muted mb-4">Save items you love and want to view later.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg px-5">
            <i class="bi bi-shop me-2"></i>Start Shopping
        </a>
    </div>
    @endif

    {{-- RELATED PRODUCTS --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-5 pt-5 border-top">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-stars text-warning me-2"></i>You Might Also Like
        </h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($relatedProducts as $product)
                {{-- Simplified Related Card to keep page light --}}
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-100" style="height: 180px; object-fit: cover;" alt="{{ $product->name }}">
                        </a>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-truncate mb-1">{{ $product->name }}</h6>
                            <div class="text-primary fw-bold mb-2">{{ number_format($product->price, 2) }} m.</div>
                            <livewire:add-to-cart-button :product-id="$product->id" :btn-color="'outline-success'" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    .fw-black { font-weight: 900; }
    
    /* Card Design */
    .product-card {
        border-radius: 1.25rem !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }

    /* Image Effects */
    .product-img-main { transition: transform 0.6s ease; }
    .product-card:hover .product-img-main { transform: scale(1.08); }

    /* Glassmorphism Badges */
    .badge-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 0.7rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .badge-new-pro { color: #198754; font-weight: 800; }
    .badge-discount-pro { color: #dc3545; font-weight: 800; animation: pulse-badge 2s infinite; }

    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Wishlist Float */
    .wishlist-float {
        transform: translateY(10px);
        opacity: 0;
        transition: all 0.3s ease;
    }
    .product-card:hover .wishlist-float {
        transform: translateY(0);
        opacity: 1;
    }

    /* Barcode Pill */
    .barcode-pill {
        background: #f8f9fa;
        border: 1px dashed #dee2e6;
        padding: 4px 10px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
    }
    .barcode-pill:hover { background: #eef0f2; border-color: #adb5bd; color: #285179; }

    .product-link:hover { color: #285179 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const barcodeElements = document.querySelectorAll('.copy-barcode-btn');
        barcodeElements.forEach(btn => {
            btn.addEventListener('click', function() {
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
</script>
@endsection