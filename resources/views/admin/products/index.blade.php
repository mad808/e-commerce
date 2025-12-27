@extends('admin.layouts.app')

@section('title', 'Product')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Products</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Add New</span>
        </a>
    </div>

    <!-- ================================================= -->
    <!-- 1. DESKTOP VIEW (Table)                           -->
    <!-- ================================================= -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Name</th>
                    <th>Barcode</th> <!-- NEW COLUMN -->
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                        @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                            <i class="bi bi-image"></i>
                        </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ Str::limit($product->name, 30) }}</td>

                    <!-- NEW: BARCODE CELL -->
                    <td class="font-monospace text-muted small">
                        @if($product->barcode)
                        <div class="text-muted small font-monospace mb-1 copy-barcode-btn"
                            data-clipboard-text="{{ $product->barcode }}"
                            title="Click to copy"
                            style="cursor: pointer; transition: 0.2s;">

                            <!-- The Icon (We give it a class to target it with JS) -->
                            <i class="bi bi-upc-scan me-1 icon-default"></i>
                            <i class="bi bi-check-circle-fill me-1 text-success icon-success d-none"></i>

                            <!-- The Text -->
                            <span class="barcode-text">{{ $product->barcode }}</span>
                        </div>
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-info text-dark bg-opacity-10 border border-info">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td>{{ number_format($product->price, 2) }} m</td>
                    <td>
                        @if($product->stock > 10)
                        <span class="text-success fw-bold">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                        <span class="text-warning fw-bold">{{ $product->stock }}</span>
                        @else
                        <span class="text-danger fw-bold">Out</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted small">
                            <i class="bi bi-eye"></i> {{ number_format($product->views) }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_active)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- 2. MOBILE VIEW (Cards)                            -->
    <!-- ================================================= -->
    <div class="d-block d-md-none bg-light p-2">
        @foreach($products as $product)
        <div class="card mb-3 border shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/60' }}" class="rounded border" width="60" height="60" style="object-fit: cover;">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1 text-dark">{{ $product->name }}</h6>

                        @if($product->barcode)
                        <div class="text-muted small font-monospace mb-1 copy-barcode-btn"
                            data-clipboard-text="{{ $product->barcode }}"
                            title="Click to copy"
                            style="cursor: pointer; transition: 0.2s;">

                            <!-- The Icon (We give it a class to target it with JS) -->
                            <i class="bi bi-upc-scan me-1 icon-default"></i>
                            <i class="bi bi-check-circle-fill me-1 text-success icon-success d-none"></i>

                            <!-- The Text -->
                            <span class="barcode-text">{{ $product->barcode }}</span>
                        </div>
                        @endif

                        <span class="badge bg-info text-dark bg-opacity-10 border border-info small">
                            {{ $product->category->name ?? 'No Category' }}
                        </span>
                    </div>
                    @if($product->is_active)
                    <span class="badge bg-success"><i class="bi bi-check"></i></span>
                    @else
                    <span class="badge bg-secondary"><i class="bi bi-eye-slash"></i></span>
                    @endif
                </div>

                <!-- STATS GRID -->
                <div class="row g-1 mb-3 small bg-light rounded p-2 mx-0 border">
                    <div class="col-3 text-center border-end">
                        <span class="text-muted d-block" style="font-size: 0.65rem;">Price</span>
                        <span class="fw-bold text-dark">{{ number_format($product->price, 1) }}m</span>
                    </div>
                    <div class="col-3 text-center border-end">
                        <span class="text-muted d-block" style="font-size: 0.65rem;">Stock</span>
                        <span class="fw-bold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">{{ $product->stock }}</span>
                    </div>
                    <div class="col-3 text-center border-end">
                        <span class="text-muted d-block" style="font-size: 0.65rem;">Views</span>
                        <span class="fw-bold text-info"><i class="bi bi-eye"></i> {{ $product->views }}</span>
                    </div>
                    <div class="col-3 text-center">
                        <span class="text-muted d-block" style="font-size: 0.65rem;">ID</span>
                        <span class="fw-bold text-dark">#{{ $product->id }}</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-info btn-sm flex-grow-1">View</a>

                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">Edit</a>

                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="flex-grow-1">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-footer bg-white">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .copy-barcode-btn:hover {
        color: #285179 !important;
        font-weight: bold;
    }
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

                // 1. Copy to Clipboard
                navigator.clipboard.writeText(barcode).then(() => {

                    // 2. Visual Feedback (Show Check Icon)
                    defaultIcon.classList.add('d-none');
                    successIcon.classList.remove('d-none');
                    textSpan.classList.add('text-success');
                    textSpan.innerText = "Copied!";

                    // 3. Revert back after 2 seconds
                    setTimeout(() => {
                        defaultIcon.classList.remove('d-none');
                        successIcon.classList.add('d-none');
                        textSpan.classList.remove('text-success');
                        textSpan.innerText = originalText;
                    }, 1500);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });
        });
    });
</script>
@endsection