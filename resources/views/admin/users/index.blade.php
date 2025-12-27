@extends('admin.layouts.app')
@section('title', 'Clients')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Client List</h5>
        @if(session('success'))
        <div class="alert alert-success py-1 px-3 mb-0 small">{{ session('success') }}</div>
        @endif
    </div>

    {{-- 1. DESKTOP VIEW --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th> {{-- New Column Header --}}
                    <th>Location</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Orders</th>
                    <th class="text-end px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px; background: {{ $user->status == 'blocked' ? '#6c757d' : 'linear-gradient(135deg, #405FA5, #60a5fa)' }};">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold d-flex align-items-center">
                                    {{ $user->name }}

                                    @if($user->admin_notes)
                                    <i class="bi bi-info-circle-fill text-warning ms-2"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Note: {{ $user->admin_notes }}"
                                        style="cursor: help;"></i>
                                    @endif
                                </div>
                                <small class="text-muted">ID: #{{ $user->id }}</small>
                            </div>
                        </div>
                    </td>

                    {{-- Phone Number Column --}}
                    <td class="fw-bold text-primary">
                        {{ $user->phone ?? '—' }}
                    </td>

                    <td>{{ $user->location->name ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>{{ $user->orders_count }}</td>
                    <td class="text-end px-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>

                            <form action="{{ route('admin.users.block', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->status == 'active' ? 'btn-outline-warning' : 'btn-success' }}">
                                    <i class="bi {{ $user->status == 'active' ? 'bi-lock' : 'bi-unlock' }}"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete user?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 2. MOBILE VIEW --}}
    <div class="d-block d-md-none bg-light p-2">
        @foreach($users as $user)
        <div class="card mb-3 border shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0">
                            {{ $user->name }}
                            @if($user->admin_notes)
                            <i class="bi bi-info-circle-fill text-warning ms-1" title="{{ $user->admin_notes }}"></i>
                            @endif
                        </h6>
                        {{-- Mobile Phone Display --}}
                        <small class="text-primary fw-bold">{{ $user->phone ?? 'No Phone' }}</small>
                    </div>
                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">{{ $user->status }}</span>
                </div>

                <div class="mb-3 small">
                    <div class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $user->email }}</div>
                    <div class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ $user->location->name ?? 'No Location' }}</div>
                </div>

                @if($user->admin_notes)
                <div class="alert alert-warning py-1 px-2 small mb-3">
                    <strong><i class="bi bi-sticky"></i> Note:</strong> {{ $user->admin_notes }}
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">Edit</a>
                    <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button class="btn {{ $user->status == 'active' ? 'btn-outline-warning' : 'btn-success' }} btn-sm w-100">
                            {{ $user->status == 'active' ? 'Block' : 'Unblock' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-footer bg-white">{{ $users->links('pagination::bootstrap-5') }}</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endsection