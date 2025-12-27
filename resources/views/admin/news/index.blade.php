@extends('admin.layouts.app')

@section('title', 'Habarlar - Admin Panel')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-newspaper me-1"></i> Habarlar
        </h5>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Täze Habar</span>
        </a>
    </div>

    <!-- ================================================= -->
    <!-- 1. DESKTOP VIEW (Table) - Hidden on Mobile        -->
    <!-- ================================================= -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th width="80">ID</th>
                    <th width="80">Surat</th>
                    <th>Ady</th>
                    <th>Gysgaça</th>
                    <th width="100">Görlen</th>
                    <th width="120">Status</th>
                    <th width="150">Senesi</th>
                    <th width="150" class="text-end">Hereketler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr>
                    <td class="fw-bold">#{{ $item->id }}</td>
                    <td>
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                        @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ Str::limit($item->title, 40) }}</td>
                    <td class="text-muted small">{{ Str::limit($item->summary, 40) }}</td>
                    <td>
                        <span class="badge bg-info text-dark bg-opacity-10 border border-info">
                            <i class="bi bi-eye"></i> {{ number_format($item->views) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.news.toggle-status', $item) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm w-100 btn-{{ $item->is_active ? 'success' : 'secondary' }}">
                                {{ $item->is_active ? 'Aktiw' : 'Passiw' }}
                            </button>
                        </form>
                    </td>
                    <td class="small text-muted">
                        {{ $item->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Pozmak isleýärsiňizmi?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">Habar ýok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ================================================= -->
    <!-- 2. MOBILE VIEW (Cards) - Visible ONLY on Mobile   -->
    <!-- ================================================= -->
    <div class="d-block d-md-none bg-light p-2">
        @foreach($news as $item)
        <div class="card mb-3 border shadow-sm">
            <div class="card-body p-3">

                <!-- Top Row: Image + Title + Status -->
                <div class="d-flex align-items-start mb-3">
                    <div class="me-3">
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="rounded border" width="60" height="60" style="object-fit: cover;">
                        @else
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px;">
                            <i class="bi bi-newspaper fs-4"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1 text-dark text-break">{{ Str::limit($item->title, 50) }}</h6>
                        <small class="text-muted">{{ $item->created_at->format('d.m.Y') }}</small>
                    </div>
                    <div>
                        <!-- Mobile Status Toggle -->
                        <form action="{{ route('admin.news.toggle-status', $item) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm rounded-pill {{ $item->is_active ? 'btn-success' : 'btn-secondary' }}" style="font-size: 0.7rem;">
                                {{ $item->is_active ? 'Aktiw' : 'Passiw' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="row g-2 mb-3 small bg-light rounded p-2 mx-0 border">
                    <div class="col-6 text-center border-end">
                        <span class="text-muted d-block">Görlen</span>
                        <span class="fw-bold text-info"><i class="bi bi-eye"></i> {{ number_format($item->views) }}</span>
                    </div>
                    <div class="col-6 text-center">
                        <span class="text-muted d-block">ID</span>
                        <span class="fw-bold text-dark">#{{ $item->id }}</span>
                    </div>
                </div>

                <!-- Bottom Row: Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                        <i class="bi bi-pencil"></i> Üýtget
                    </a>
                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Pozmak isleýärsiňizmi?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm w-100">
                            <i class="bi bi-trash"></i> Poz
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach

        @if($news->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted">Habar ýok.</p>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="card-footer bg-white">
        {{ $news->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection