<div class="container py-4">
    <style>
        /* Sidebar & Layout Styles */
        .filter-sidebar {
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .filter-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .filter-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .filter-sidebar::-webkit-scrollbar-thumb {
            background: #285179;
            border-radius: 10px;
        }

        /* Pro Card Styles (Global for this page) */
        .product-card-pro {
            border-radius: 20px !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }

        .product-card-pro:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12) !important;
        }

        .image-container-pro {
            height: 240px;
            overflow: hidden;
            background: #fdfdfd;
            border-bottom: 1px solid #f8f9fa;
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

        .glass-pill {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
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

        .barcode-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8f9fa;
            border: 1px dashed #dee2e6;
            padding: 4px 12px;
            border-radius: 10px;
            font-family: monospace;
            font-size: 0.75rem;
            color: #6c757d;
            cursor: pointer;
        }

        .fw-black {
            font-weight: 900;
        }

        .wishlist-float-btn {
            transform: scale(0.85) translateX(10px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-card-pro:hover .wishlist-float-btn {
            transform: scale(1) translateX(0);
            opacity: 1;
        }

        /* Pagination Override (assuming Bootstrap 5) */
        .pagination .page-link {
            border: none;
            margin: 0 5px;
            border-radius: 8px;
            color: #285179;
            font-weight: 600;
        }

        .pagination .page-item.active .page-link {
            background-color: #285179;
            color: white;
        }
    </style>

    <nav aria-label="breadcrumb" class="mb-4 bg-light rounded-pill px-4 py-2 d-inline-block">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-dark fw-bold">Shop</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm filter-sidebar">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                        <i class="bi bi-funnel me-2" style="color: #285179;"></i> Filters
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label small text-muted fw-bold mb-2">Search Products</label>
                        <input type="text" wire:model.live.debounce.300ms="q" class="form-control border-0 bg-light" placeholder="Type to search...">
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-secondary">Categories</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="" id="cat_all" wire:model.live="category_id">
                                <label class="form-check-label" for="cat_all">All Categories</label>
                            </div>
                            @foreach($categories as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="{{ $cat->id }}" id="cat_{{ $cat->id }}" wire:model.live="category_id">
                                <label class="form-check-label d-flex justify-content-between w-100 pe-2" for="cat_{{ $cat->id }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="badge bg-light text-dark">{{ $cat->products_count }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-secondary">Price Range</h6>
                        <div class="d-flex gap-2">
                            <input type="number" wire:model.live.debounce.500ms="min_price" class="form-control form-control-sm bg-light border-0" placeholder="Min">
                            <input type="number" wire:model.live.debounce.500ms="max_price" class="form-control form-control-sm bg-light border-0" placeholder="Max">
                        </div>
                    </div>

                    <button wire:click="clearFilters" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">Clear All</button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
                <div>
                    <span class="fw-bold text-primary">{{ $products->total() }}</span>
                    <span class="text-muted small ms-1">Products found</span>
                </div>
                <select wire:model.live="sort" class="form-select form-select-sm border-0 bg-light fw-bold" style="width: 160px;">
                    <option value="newest">Newest First</option>
                    <option value="price_asc">Price: Low-High</option>
                    <option value="price_desc">Price: High-Low</option>
                </select>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.copy-barcode-btn')) {
                const btn = e.target.closest('.copy-barcode-btn');
                const barcode = btn.getAttribute('data-clipboard-text');
                const defaultIcon = btn.querySelector('.icon-default');
                const successIcon = btn.querySelector('.icon-success');
                const textSpan = btn.querySelector('.barcode-text');
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
            }
        });
    });
</script>