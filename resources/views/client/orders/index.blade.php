@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-brand m-0">Sargytlarym (My Orders)</h2>
            <p class="text-muted small m-0">Track your purchase history</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm fw-bold d-none d-md-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
        </a>
    </div>

    @if($orders->count() > 0)
    <div class="row">
        <div class="col-12">

            <!-- ============================================= -->
            <!-- 1. DESKTOP VIEW (Table)                       -->
            <!-- ============================================= -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Order ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-brand">#{{ $order->id }}</span>
                                </td>
                                <td class="text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    @php
                                    $statusColor = match($order->status) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                    };
                                    $statusText = match($order->status) {
                                    'pending' => 'Garaşylýar',
                                    'processing' => 'Taýýarlanýar',
                                    'shipped' => 'Ugradyldy',
                                    'delivered' => 'Gowşuryldy',
                                    'cancelled' => 'Ýatyryldy',
                                    default => $order->status
                                    };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} rounded-pill px-3">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $order->items_count }} items</span>
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ number_format($order->total_price, 2) }} m
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-sm btn-outline-brand rounded-pill px-4 fw-bold">
                                        Details <i class="bi bi-chevron-right" style="font-size: 0.8rem;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ============================================= -->
            <!-- 2. MOBILE VIEW (Cards)                        -->
            <!-- ============================================= -->
            <div class="d-md-none d-flex flex-column gap-3">
                @foreach($orders as $order)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-brand">Order #{{ $order->id }}</span>
                            <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                            <div>
                                <small class="text-muted d-block">Status</small>
                                @php
                                $statusColor = match($order->status) {
                                'pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger', default => 'secondary'
                                };
                                @endphp
                                <span class="badge bg-{{ $statusColor }} text-white rounded-pill">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Total</small>
                                <span class="fw-bold text-dark">{{ number_format($order->total_price, 2) }} m</span>
                            </div>
                        </div>

                        <a href="{{ route('client.orders.show', $order->id) }}" class="btn btn-brand w-100 rounded-pill fw-bold">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
    @else

    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
            <i class="bi bi-box-seam text-muted display-4 opacity-50"></i>
        </div>
        <h3 class="fw-bold text-dark">No orders found</h3>
        <p class="text-muted mb-4">Looks like you haven't bought anything yet.</p>
        <a href="{{ route('home') }}" class="btn btn-brand rounded-pill px-5 py-2 fw-bold shadow-sm">
            Start Shopping
        </a>
    </div>
    @endif
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

    /* Button Outline Brand */
    .btn-outline-brand {
        color: #285179;
        border-color: #285179;
        transition: all 0.3s ease;
    }

    .btn-outline-brand:hover {
        background-color: #285179;
        color: white;
    }

    /* Solid Brand Button */
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
        box-shadow: 0 4px 12px rgba(40, 81, 121, 0.3);
    }
</style>
@endsection