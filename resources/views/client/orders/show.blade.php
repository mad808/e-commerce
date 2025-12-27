@extends('layouts.app')

@section('title', 'Sargyt #' . $order->id)

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h2 class="fw-bold text-brand mb-0">Sargyt #{{ $order->id }}</h2>

                <!-- Dynamic Status Badge -->
                @php
                $statusConfig = match($order->status) {
                'pending' => ['color' => 'warning', 'icon' => 'bi-hourglass-split', 'label' => 'Garaşylýar'],
                'processing' => ['color' => 'info', 'icon' => 'bi-gear-wide-connected', 'label' => 'Taýýarlanýar'],
                'shipped' => ['color' => 'primary', 'icon' => 'bi-truck', 'label' => 'Ugradyldy'],
                'delivered' => ['color' => 'success', 'icon' => 'bi-check-circle-fill', 'label' => 'Gowşuryldy'],
                'cancelled' => ['color' => 'danger', 'icon' => 'bi-x-circle-fill', 'label' => 'Ýatyryldy'],
                default => ['color' => 'secondary', 'icon' => 'bi-question-circle', 'label' => $order->status]
                };
                @endphp
                <span class="badge bg-{{ $statusConfig['color'] }} bg-opacity-10 text-{{ $statusConfig['color'] }} border border-{{ $statusConfig['color'] }} rounded-pill px-3 ms-2 d-none d-md-inline-block">
                    <i class="{{ $statusConfig['icon'] }} me-1"></i> {{ $statusConfig['label'] }}
                </span>
            </div>
            <p class="text-muted mb-0 small mt-1">
                <i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d F Y, H:i') }}
            </p>

            <!-- Mobile Badge (Visible only on small screens) -->
            <div class="mt-2 d-md-none">
                <span class="badge bg-{{ $statusConfig['color'] }} text-white rounded-pill">
                    {{ $statusConfig['label'] }}
                </span>
            </div>
        </div>

        <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm fw-bold hover-lift">
            <i class="bi bi-arrow-left me-1"></i> Yzyna
        </a>
    </div>

    <div class="row g-4">

        <!-- Left: Order Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-basket me-2 text-brand"></i> Harytlar (Items)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Haryt</th>
                                    <th class="text-center">Sany</th>
                                    <th class="text-end pe-4">Jemi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('product.show', $item->product->slug) }}" class="flex-shrink-0">
                                                @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded-3 border" width="60" height="60" style="object-fit: cover;">
                                                @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                                @endif
                                            </a>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>

                                                @if(!empty($item->attributes))
                                                <div class="mb-1">
                                                    @foreach($item->attributes as $key => $value)
                                                    <span class="badge bg-light text-dark border-0 fw-normal p-0 me-2" style="font-size: 0.75rem;">
                                                        <span class="text-muted">{{ ucfirst($key) }}:</span> <strong>{{ $value }}</strong>
                                                    </span>
                                                    @endforeach
                                                </div>
                                                @endif

                                                <small class="text-muted d-block">{{ number_format($item->price, 2) }} m / sany</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3">x{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-brand">
                                        {{ number_format($item->price * $item->quantity, 2) }} m
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Total -->
                <div class="card-footer bg-light p-4">
                    <div class="row">
                        <!-- Left: Order Summary Info -->
                        <div class="col-md-7 col-lg-8 mb-3 mb-md-0">
                            <div class="d-flex align-items-center h-100">
                                <div class="bg-white rounded-3 p-3 border w-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-brand bg-opacity-10 rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-box-seam text-brand fs-5"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">JEMI HARYT</small>
                                                <span class="fw-bold text-dark">{{ $order->items->sum('quantity') }} sany</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-cash-stack text-success fs-5"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">TÖLEG USULY</small>
                                                <span class="fw-bold text-dark">Nagt</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Price Breakdown -->
                        <div class="col-md-5 col-lg-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small fw-bold">HARYTLAR:</span>
                                <span class="fw-bold">{{ number_format($order->total_price - $order->delivery_price, 2) }} m</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted small fw-bold">ELTIP BERMEK:</span>
                                <span class="text-brand fw-bold">+ {{ number_format($order->delivery_price, 2) }} m</span>
                            </div>

                            <hr class="my-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-dark fw-bold">JEMI TÖLEG:</span>
                                <h3 class="fw-bold text-brand mb-0">{{ number_format($order->total_price, 2) }} m</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Delivery Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-truck me-2 text-brand"></i> Gowşuryş Maglumaty</h6>
                </div>
                <div class="card-body p-4">

                    <div class="mb-4">
                        <label class="text-muted text-uppercase small fw-bold mb-1">Müşderi</label>
                        <p class="mb-0 fw-semibold text-dark">{{ $order->full_name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted text-uppercase small fw-bold mb-1">Telefon</label>
                        <p class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-telephone text-brand me-1"></i> {{ $order->phone }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted text-uppercase small fw-bold mb-1">Şäher / Sebitleýin</label>
                        <p class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-truck text-brand me-1"></i>
                            {{ $order->location->name ?? 'Görkezilmedi' }}
                            <span class="text-muted small">({{ number_format($order->delivery_price, 2) }} m)</span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted text-uppercase small fw-bold mb-1">Doly Salgy</label>
                        <p class="mb-0 text-dark bg-light p-3 rounded-3 border">
                            <i class="bi bi-geo-alt text-brand me-1"></i> {{ $order->address }}
                        </p>
                    </div>

                    @if($order->notes)
                    <div class="mb-4">
                        <label class="text-muted text-uppercase small fw-bold mb-1">Bellikler</label>
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark small mb-0">
                            <i class="bi bi-sticky me-1"></i> {{ $order->notes }}
                        </div>
                    </div>
                    @endif

                    <hr class="opacity-25 my-4">

                    <div class="text-center">
                        <p class="small text-muted mb-2">Sargyt barada soragyňyz barmy?</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-brand w-100 rounded-pill fw-bold">
                            <i class="bi bi-headset me-1"></i> Habarlaşmak
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Styles -->
<style>
    /* Theme Colors */
    .text-brand {
        color: #285179 !important;
    }

    .bg-brand {
        background-color: #285179 !important;
    }

    /* Button Styles */
    .btn-outline-brand {
        color: #285179;
        border-color: #285179;
        transition: all 0.3s ease;
    }

    .btn-outline-brand:hover {
        background-color: #285179;
        color: white;
    }

    /* Links */
    .hover-text-brand:hover {
        color: #285179 !important;
        text-decoration: underline !important;
        transition: 0.2s;
    }

    /* Card Lift Effect */
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }
</style>
@endsection