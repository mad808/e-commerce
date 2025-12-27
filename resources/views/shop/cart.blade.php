@extends('layouts.app')

@section('title', 'My Shopping Cart')

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold text-brand mb-0">Shopping Cart</h2>
        <span class="badge bg-light text-secondary ms-3 border rounded-pill px-3 py-2">{{ $cartItems->count() }} Items</span>
    </div>

    @if($cartItems->count() > 0)
    <div class="row g-4">

        <div class="col-lg-8">
            <div class="d-flex flex-column gap-3">
                @foreach($cartItems as $item)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition-all">
                    <div class="card-body p-3">
                        <div class="row align-items-center">

                            <div class="col-3 col-md-2">
                                <a href="{{ route('product.show', $item->product->slug) }}">
                                    @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded-3 border" style="width: 100%; aspect-ratio: 1/1; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted border" style="width: 100%; aspect-ratio: 1/1;">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                    @endif
                                </a>
                            </div>

                            <div class="col-9 col-md-4">
                                <h6 class="fw-bold mb-1">
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="text-dark text-decoration-none hover-text-brand">
                                        {{ $item->product->name }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">{{ $item->product->category->name ?? 'General' }}</p>

                                @if($item->attributes)
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach($item->attributes as $key => $value)
                                    <span class="badge bg-light text-dark border fw-normal" style="font-size: 0.7rem;">
                                        <span class="text-muted">{{ $key }}:</span> {{ $value }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif

                                <div class="d-md-none fw-bold text-brand mt-1">
                                    {{ number_format($item->product->price, 2) }} m
                                </div>
                            </div>

                            <div class="col-6 col-md-3 mt-3 mt-md-0">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <div class="input-group input-group-sm border rounded-pill overflow-hidden" style="max-width: 120px;">
                                        <button type="submit" name="action" value="decrease"
                                            class="btn btn-light border-0 px-3 hover-bg-gray"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>

                                        <input type="text" class="form-control text-center border-0 bg-white fw-bold"
                                            value="{{ $item->quantity }}" readonly>

                                        <button type="submit" name="action" value="increase"
                                            class="btn btn-light border-0 px-3 hover-bg-gray">
                                            +
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-6 col-md-3 mt-3 mt-md-0 text-end d-flex flex-column align-items-end justify-content-center">
                                <span class="fw-bold fs-5 text-brand d-none d-md-block">
                                    {{ number_format($item->product->price * $item->quantity, 2) }} m
                                </span>

                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-md-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link text-danger p-0 text-decoration-none small hover-opacity" title="Remove Item">
                                        <i class="bi bi-trash me-1"></i> <span class="d-none d-md-inline">Remove</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-checkout">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Subtotal</span>
                        <span>{{ number_format($total, 2) }} m</span>
                    </div>
                    
                    <!-- <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Shipping</span>
                        <span class="text-success fw-bold">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 text-muted">
                        <span>Tax (0%)</span>
                        <span>0.00 m</span>
                    </div> -->

                    <div class="border-top border-bottom py-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-dark">Total</span>
                            <span class="fs-4 fw-bold text-brand">{{ number_format($total, 2) }} m</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-brand w-100 py-3 rounded-pill fw-bold shadow-sm hover-scale">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <div class="mt-4 text-center">
                        <p class="small text-muted mb-2"><i class="bi bi-shield-lock me-1"></i> Secure Checkout</p>
                        <div class="d-flex justify-content-center gap-2 opacity-50">
                            <i class="bi bi-credit-card fs-4"></i>
                            <i class="bi bi-paypal fs-4"></i>
                            <i class="bi bi-bank fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @else
    <div class="text-center py-5 my-5">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-cart-x text-muted display-4 opacity-50"></i>
        </div>
        <h3 class="fw-bold text-dark">Your cart is empty</h3>
        <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
        <a href="{{ route('shop') }}" class="btn btn-brand rounded-pill px-5 py-2 fw-bold shadow-sm">
            Start Shopping
        </a>
    </div>
    @endif

</div>

<style>
    /* 1. STICKY FIX: Slides UNDER the navbar */
    .sticky-checkout {
        position: -webkit-sticky;
        position: sticky;
        /* Increase this 'top' value if your navbar is taller */
        top: 100px;
        /* Low z-index keeps it behind the navbar/search bar (usually 1030+) */
        z-index: 10 !important;
    }

    /* Force the Main Navbar to stay on top */
    nav.navbar,
    .header-main,
    header {
        z-index: 1050 !important;
    }

    /* 2. Theme Colors */
    .text-brand {
        color: #285179 !important;
    }

    .bg-brand {
        background-color: #285179 !important;
    }

    .btn-brand {
        background-color: #285179;
        border-color: #285179;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-brand:hover {
        background-color: #1a3652;
        border-color: #1a3652;
        color: white;
        transform: translateY(-2px);
    }

    /* 3. Card Effects */
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-text-brand:hover {
        color: #285179 !important;
        text-decoration: underline !important;
    }

    /* 4. Buttons & Inputs */
    .hover-bg-gray:hover {
        background-color: #e9ecef;
    }

    .hover-opacity:hover {
        opacity: 0.7;
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }
</style>
@endsection