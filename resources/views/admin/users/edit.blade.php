@extends('admin.layouts.app')
@section('title', 'Ulanyjyny üýtgetmek')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Ulanyjylar</a></li>
                    <li class="breadcrumb-item active">Üýtgetmek #{{ $user->id }}</li>
                </ol>
            </nav>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3 border-0">
                                <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i> Profil maglumatlary</h5>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Doly ady</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Email adresi</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Telefon belgisi</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+993...">
                                </div>

                                <hr class="my-4 text-light">

                                <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-warning"></i> Paroly täzelemek</h6>
                                <p class="small text-muted">Eger paroly üýtgetmek isleseňiz aşakdaky öýjükleri dolduryň. Bolmasa boş goýuň.</p>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Täze parol</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Paroly tassyklaň</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Status</label>
                                    <select name="status" class="form-select shadow-none">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Aktiw</option>
                                        <option value="blocked" {{ $user->status == 'blocked' ? 'selected' : '' }}>Bloklanan</option>
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted">Ulgamdaky roly</label>
                                    <select name="role" id="roleSelect" class="form-select shadow-none border-primary-subtle" onchange="togglePermissions()">
                                        <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Client (Müşderi)</option>
                                        <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator (Işgär)</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Dolandyryjy)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4 {{ $user->role == 'client' ? 'd-none' : '' }}" id="permissionsCard" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-key me-2"></i> Bölüm hukuklary</h6>
                            </div>
                            <div class="card-body p-4 pt-0">
                                @php
                                $availablePermissions = [
                                'products' => 'Harytlar we Kategoriýalar',
                                'orders' => 'Sargytlar we Hasabatlar',
                                'sliders' => 'Bannerler we Täzelikler',
                                'messages' => 'Hatlar we Aragatnaşyk',
                                'users' => 'Ulanyjy dolandyryşy',
                                'settings' => 'Sazlamalar (Settings)'
                                ];
                                @endphp

                                @foreach($availablePermissions as $key => $label)
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[{{ $key }}]"
                                        id="perm_{{ $key }}" value="1"
                                        {{ $user->hasPermission($key) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="perm_{{ $key }}">{{ $label }}</label>
                                </div>
                                @endforeach
                                <div class="mt-3 p-2 bg-light rounded-3 small text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Admin roly üçin hemme hukuklar açyk bolýar.
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <label class="form-label small fw-bold text-danger"><i class="bi bi-sticky me-1"></i> Admin Bellikleri</label>
                                <textarea name="admin_notes" class="form-control border-danger-subtle bg-danger bg-opacity-10 shadow-none" rows="3" placeholder="Içki bellikler...">{{ old('admin_notes', $user->admin_notes) }}</textarea>
                                <div class="form-text small text-muted">Bu bellikleri müşderi görmeýär.</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                                <i class="bi bi-check-lg me-1"></i> Ýatda sakla
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light py-2 text-muted">Yza gaýt</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePermissions() {
        const role = document.getElementById('roleSelect').value;
        const card = document.getElementById('permissionsCard');
        if (role === 'client') {
            card.classList.add('d-none');
        } else {
            card.classList.remove('d-none');
        }
    }
</script>
@endsection