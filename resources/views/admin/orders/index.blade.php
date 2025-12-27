@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Recent Orders</h5>
    </div>

    <!-- ================================================= -->
    <!-- 1. DESKTOP VIEW (Table) - Hidden on Mobile        -->
    <!-- ================================================= -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="fw-bold">#{{ $order->id }}</td>

                    <td>
                        <div class="fw-bold">{{ $order->full_name }}</div>
                        <small class="text-muted">{{ $order->phone }}</small>
                    </td>

                    <td class="fw-bold">
                        #{{ $order->id }}
                        <span class="badge rounded-pill bg-light text-dark border ms-1" style="font-size: 0.65rem;">
                            {{ $order->items->count() }} items
                        </span>
                    </td>
                    
                    <td class="fw-bold text-success">{{ number_format($order->total_price, 2) }} m</td>
                    <td>
                        @if($order->status == 'pending') <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status == 'processing') <span class="badge bg-info text-dark">Processing</span>
                        @elseif($order->status == 'shipped') <span class="badge bg-primary">Shipped</span>
                        @elseif($order->status == 'delivered') <span class="badge bg-success">Delivered</span>
                        @else <span class="badge bg-danger">Cancelled</span> @endif
                    </td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- 2. MOBILE VIEW (Cards) - Visible ONLY on Mobile   -->
    <!-- ================================================= -->
    <div class="d-block d-md-none bg-light p-2">
        @foreach($orders as $order)
        <div class="card mb-3 border shadow-sm">
            <div class="card-body p-3">

                <!-- Row 1: ID and Date -->
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                    <span class="fw-bold text-dark">Order #{{ $order->id }}</span>
                    <small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small>
                </div>

                <!-- Row 2: Customer Info -->
                <div class="mb-3">
                    <div class="d-flex align-items-center text-secondary mb-1">
                        <i class="bi bi-person me-2"></i>
                        <span class="fw-bold text-dark">{{ $order->full_name }}</span>
                    </div>
                    <div class="d-flex align-items-center text-secondary">
                        <i class="bi bi-telephone me-2"></i>
                        <span>{{ $order->phone }}</span>
                    </div>
                </div>

                <!-- Row 3: Price & Status -->
                <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded">
                    <div>
                        <small class="text-muted d-block" style="font-size: 0.7rem;">TOTAL</small>
                        <span class="fw-bold text-success fs-5">{{ number_format($order->total_price, 2) }} m</span>
                    </div>
                    <div>
                        @if($order->status == 'pending') <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status == 'processing') <span class="badge bg-info text-dark">Processing</span>
                        @elseif($order->status == 'shipped') <span class="badge bg-primary">Shipped</span>
                        @elseif($order->status == 'delivered') <span class="badge bg-success">Delivered</span>
                        @else <span class="badge bg-danger">Cancelled</span> @endif
                    </div>
                </div>

                <!-- Row 4: Action Button -->
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary w-100 fw-bold">
                    View Order Details <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>
        </div>
        @endforeach

        @if($orders->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted">No orders found.</p>
        </div>
        @endif
    </div>

    <div class="card-footer bg-white">{{ $orders->links('pagination::bootstrap-5') }}</div>
</div>
@endsection