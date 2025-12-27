@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="row">
    <!-- Left: Order Items -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                @foreach($order->items as $item)
                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                    <img src="{{ asset('storage/' . $item->product->image) }}" width="60" class="rounded border me-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $item->product->name }}</h6>

                        @if($item->attributes)
                        <div class="mt-1">
                            @foreach($item->attributes as $key => $value)
                            <span class="badge bg-light text-dark border fw-normal me-1" style="font-size: 0.7rem;">
                                <span class="text-muted">{{ ucfirst($key) }}:</span> {{ $value }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <small class="text-muted">Unit Price: {{ number_format($item->price, 2) }} m</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">x{{ $item->quantity }}</div>
                        <div class="text-primary fw-bold">{{ number_format($item->price * $item->quantity, 2) }} m</div>
                    </div>
                </div>
                @endforeach
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Harytlaryň jemi:</span>
                        <span class="fw-bold">{{ number_format($order->total_price - ($order->delivery_price ?? 0), 2) }} m</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Eltip bermek hyzmaty:</span>
                        <span class="fw-bold">+ {{ number_format($order->delivery_price ?? 0, 2) }} m</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <h5 class="mb-0">Umumy jemi:</h5>
                        <h4 class="text-success fw-bold mb-0">{{ number_format($order->total_price, 2) }} m</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Customer & Status -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-3">Order Status</h6>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <select name="status" class="form-select mb-3" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
                <small class="text-muted">Change status to update order progress.</small>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-3">Customer Details</h6>
                <div class="mb-2"><strong>Name:</strong> {{ $order->full_name }}</div>
                <div class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</div>
                <div class="mb-2"><strong>Location:</strong> {{ $order->location->name ?? 'Default' }}</div>
                <div class="mb-3"><strong>Address:</strong><br> {{ $order->address }}</div>
                @if($order->notes)
                <div class="alert alert-warning small mb-0">
                    <strong>Note:</strong> {{ $order->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection