@props(['product', 'btnColor' => 'success'])

@php
$isNew = $product->created_at->diffInDays(now()) < 7;
    $hasDiscount=$product->old_price && $product->old_price > $product->price;
    @endphp

    <div class="card h-100 product-card-pro border-0 shadow-sm bg-white overflow-hidden">
        <div class="image-container-pro position-relative">
            <div class="badge-stack position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between align-items-start" style="z-index: 5;">
                <div class="d-flex flex-column gap-2">
                    @if($isNew)
                    <span class="glass-pill badge-new">TÄZE</span>
                    @endif

                    @if($hasDiscount)
                    @php $percent = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                    <span class="glass-pill badge-sale">-{{ $percent }}%</span>
                    @endif
                </div>

                <div class="wishlist-float-btn">
                    <livewire:toggle-favorite :product-id="$product->id" :key="'fav-'.$product->id" />
                </div>
            </div>

            <a href="{{ route('product.show', $product->slug) }}" class="d-block h-100">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top-pro" alt="{{ $product->name }}">
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
                        <span class="current-price fw-black fs-4 text-dark">
                            {{ number_format($product->price, 2) }} <small class="fs-6">m.</small>
                        </span>
                        @if($hasDiscount)
                        <span class="old-price-pro text-muted text-decoration-line-through ms-2 small">
                            {{ number_format($product->old_price, 2) }} m.
                        </span>
                        @endif
                    </div>
                </div>

                <livewire:add-to-cart-button :product-id="$product->id" :btn-color="$btnColor" :key="'cart-'.$product->id" />
            </div>
        </div>
    </div>