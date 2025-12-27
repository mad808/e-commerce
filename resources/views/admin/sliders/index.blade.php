@extends('admin.layouts.app')

@section('title', 'Banner')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Banners & Sliders</h5>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Add New</span>
        </a>
    </div>

    <!-- ================================================= -->
    <!-- 1. DESKTOP VIEW (Table) - Hidden on Mobile        -->
    <!-- ================================================= -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th width="120">Preview</th>
                    <th>Title / Type</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sliders as $slider)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $slider->image) }}" class="rounded border" width="100" height="50" style="object-fit: cover;">
                    </td>
                    <td>
                        <div class="fw-bold">{{ $slider->title ?? 'No Title' }}</div>
                        <small class="text-muted text-uppercase" style="font-size: 0.75rem;">{{ str_replace('_', ' ', $slider->type) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $slider->sort_order }}</span>
                    </td>
                    <td>
                        @if($slider->is_active)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this banner?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
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
        @foreach($sliders as $slider)
        <div class="card mb-3 border shadow-sm">
            <!-- Image at the top for mobile -->
            <div style="height: 150px; overflow: hidden;" class="border-bottom">
                <img src="{{ asset('storage/' . $slider->image) }}" class="w-100 h-100" style="object-fit: cover;">
            </div>

            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $slider->title ?? 'No Title' }}</h6>
                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">
                            {{ str_replace('_', ' ', $slider->type) }}
                        </small>
                    </div>
                    <span class="badge bg-light text-dark border">#{{ $slider->sort_order }}</span>
                </div>

                <div class="mb-3">
                    @if($slider->is_active)
                    <span class="badge bg-success w-100">Active</span>
                    @else
                    <span class="badge bg-secondary w-100">Inactive</span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Delete this banner?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        @if($sliders->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted">No banners found.</p>
        </div>
        @endif
    </div>

    <div class="card-footer bg-white">
        {{ $sliders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection