@extends('admin.layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h4 class="fw-bold">Search Results for: <span class="text-primary">"{{ $query }}"</span></h4>
    </div>

    <div class="row g-4">

        {{-- Products Section --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-box-seam me-2"></i> Products ({{ $products->count() }})</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($products as $product)
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="40" height="40" class="rounded me-3 border" style="object-fit: cover;">
                        @else
                        <div class="bg-light rounded me-3 border d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                        @endif
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark small text-truncate">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->barcode ?? 'No Barcode' }}</small>
                        </div>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">No products found</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Categories Section --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-success"><i class="bi bi-diagram-3 me-2"></i> Categories ({{ $categories->count() }})</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($categories as $category)
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" width="40" height="40" class="rounded-circle me-3 border" style="object-fit: cover;">
                        @else
                        <div class="bg-light rounded-circle me-3 border d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-folder text-muted"></i>
                        </div>
                        @endif
                        <div>
                            <div class="fw-bold text-dark small">{{ $category->name }}</div>
                            <small class="text-muted">{{ $category->parent ? 'Sub of ' . $category->parent->name : 'Main' }}</small>
                        </div>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">No categories found</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Orders Section (Added Phone Display) --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-warning"><i class="bi bi-cart me-2"></i> Orders ({{ $orders->count() }})</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($orders as $order)
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-bold text-dark small">Order #{{ $order->id }}</span>
                            <span class="badge bg-opacity-10 {{ $order->status == 'cancelled' ? 'bg-danger text-danger' : 'bg-info text-info' }} small">{{ $order->status }}</span>
                        </div>
                        <div class="small text-muted text-truncate">{{ $order->full_name }}</div>
                        <div class="small text-primary fw-bold mt-1"><i class="bi bi-telephone small"></i> {{ $order->phone }}</div>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">No orders found</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Clients Section (Added Blocked Badge, Notes, and Phone) --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-info"><i class="bi bi-people me-2"></i> Clients ({{ $users->count() }})</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($users as $user)
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-dark small">
                                {{ $user->name }}
                                @if($user->admin_notes)
                                <i class="bi bi-info-circle-fill text-warning ms-1" data-bs-toggle="tooltip" title="{{ $user->admin_notes }}"></i>
                                @endif
                            </div>
                            @if($user->status == 'blocked')
                            <span class="badge bg-danger x-small">Blocked</span>
                            @endif
                        </div>
                        <small class="text-muted d-block">{{ $user->email }}</small>
                        <small class="text-primary fw-bold">{{ $user->phone ?? 'No Phone' }}</small>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">No clients found</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Initialize tooltips for Admin Notes
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endsection