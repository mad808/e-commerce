@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">

    <div class="mb-5 text-center">
        <h2 class="fw-bold text-brand display-6">Sargyt Etmek (Checkout)</h2>
        <p class="text-muted">Gowşuryş maglumatlaryny dolduryň</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row g-4 g-lg-5">

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-geo-alt-fill text-brand me-2"></i> Eltip bermek maglumaty
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Doly adyňyz</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="full_name" class="form-control bg-light border-0 py-2"
                                    value="{{ old('full_name', $user->name) }}" required placeholder="Mysal: Myrat">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Telefon belgiňiz</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input type="text" name="phone" class="form-control bg-light border-0 py-2"
                                    value="{{ old('phone', $user->phone) }}" required placeholder="+993 6X XX XX XX">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Şäher / Sebitleýin</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-truck text-muted"></i></span>
                                <select name="location_id" id="location_select" class="form-select bg-light border-0 py-2" required>
                                    <option value="" data-fee="0" selected disabled>Saýlaň...</option>
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" data-fee="{{ $loc->delivery_price }}">
                                        {{ $loc->name }} (+{{ number_format($loc->delivery_price, 0) }} m)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Doly salgyňyz</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-map text-muted"></i></span>
                                <textarea name="address" class="form-control bg-light border-0 py-2" rows="3"
                                    required placeholder="Köçe, jaý, öý belgisi...">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-uppercase text-muted">Bellik (hökman däl)</label>
                            <textarea name="notes" class="form-control bg-light border-0 py-2" rows="2"
                                placeholder="Kuryer üçin goşmaça bellik..."></textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-checkout">
                    <div class="card-header bg-brand text-white py-3 px-4 rounded-top-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i> Sargyt Jemi</h5>
                    </div>
                    <div class="card-body p-4">

                        <ul class="list-group list-group-flush mb-4 custom-scroll" style="max-height: 300px; overflow-y: auto;">
                            @foreach($cartItems as $item)
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-3 border-bottom-dashed">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded-3 border" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 0.6rem;">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>

                                    <div style="line-height: 1.2;">
                                        <h6 class="my-0 text-dark small fw-bold">{{ Str::limit($item->product->name, 25) }}</h6>
                                        @if($item->attributes)
                                        <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                            @foreach($item->attributes as $key => $value)
                                            <span class="me-2">{{ $key }}: <strong>{{ $value }}</strong></span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <span class="fw-semibold text-dark small">
                                    {{ number_format($item->product->price * $item->quantity, 2) }} m
                                </span>
                            </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Harytlar:</span>
                            <span class="fw-bold">{{ number_format($total, 2) }} m</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Eltip bermek:</span>
                            <span id="delivery_display" class="text-brand fw-bold">0.00 m</span>
                        </div>

                        <div class="border-top border-2 border-light py-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-dark">Jemi:</span>
                                <span class="fs-3 fw-bold text-brand" id="total_display">{{ number_format($total, 2) }} m</span>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-lg rounded-pill shadow-sm fw-bold hover-lift">
                                Sargydy Tassykla <i class="bi bi-check-circle-fill ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center mt-3 text-muted opacity-75 small">
                            <i class="bi bi-shield-lock-fill me-1"></i> Howpsuz Töleg
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
    .sticky-checkout {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        z-index: 10 !important;
    }

    nav.navbar,
    .header-main {
        position: sticky;
        top: 0;
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
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 81, 121, 0.3);
        color: white;
    }

    /* 3. Form States */
    .form-control:focus,
    .form-select:focus {
        box-shadow: none !important;
        background-color: #fff !important;
        border: 1px solid #285179 !important;
    }

    .input-group-text {
        color: #285179;
        background-color: #f8f9fa;
    }

    /* 4. Decorations */
    .border-bottom-dashed {
        border-bottom: 1px dashed #e0e0e0 !important;
    }

    /* 5. Custom Scrollbar for the summary list */
    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: #ccc #f9f9f9;
    }

    .custom-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #28517950;
        border-radius: 10px;
    }

    /* 6. Animations */
    .hover-lift {
        transition: transform 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
    }

    /* Ensure images don't overflow */
    .product-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationSelect = document.getElementById('location_select');
        const deliveryDisplay = document.getElementById('delivery_display');
        const totalDisplay = document.getElementById('total_display');

        // Pass the PHP subtotal safely to JS
        const subtotal = parseFloat("{{ $total }}");

        locationSelect.addEventListener('change', function() {
            // Get the fee from the data-fee attribute of the selected <option>
            const selectedOption = this.options[this.selectedIndex];
            const fee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;

            const finalTotal = subtotal + fee;

            // Update the text in the summary box
            deliveryDisplay.textContent = fee.toFixed(2) + ' m';
            totalDisplay.textContent = finalTotal.toFixed(2) + ' m';
        });
    });
</script>
@endsection