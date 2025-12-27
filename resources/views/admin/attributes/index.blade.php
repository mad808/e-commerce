@extends('admin.layouts.app')

@section('title', 'Attributes')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2"></i> Product Attributes</h5>
        <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Add New
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributes as $attr)
                <tr>
                    <td class="fw-bold">#{{ $attr->id }}</td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">
                            {{ $attr->name }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $attr->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.attributes.edit', $attr->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.attributes.destroy', $attr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this attribute?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No attributes found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $attributes->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection