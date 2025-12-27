@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-4">
            
            <!-- Royal Blue Header -->
            <div class="card-header border-0 py-4 text-center" style="background: linear-gradient(135deg, #405FA5, #60a5fa); font-size: 1.2rem;">
                <div class="mb-3">
                    <!-- Large Profile Icon -->
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 100px; height: 100px; border: 4px solid rgba(255,255,255,0.3);">
                        <i class="bi bi-person-fill" style="font-size: 3.5rem; color: #305f8eff;"></i>
                    </div>
                </div>
                <h3 class="text-white fw-bold mb-0">{{ $user->name }}</h3>
                <p class="text-white-50 mb-0">{{ $user->email }}</p>
                <span class="badge bg-white mt-2 px-3 rounded-pill text-uppercase" style="color: #60a5fa;">Administrator</span>
            </div>

            <div class="card-body p-5">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-4 text-secondary fw-bold border-bottom pb-2">Account Information</h5>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <h5 class="mb-4 text-secondary fw-bold border-bottom pb-2 mt-5">Security (Optional)</h5>

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label text-muted">Current Password</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control bg-light border-0" placeholder="Enter current password to change it">
                            <button class="btn btn-light border-0 text-secondary" type="button" onclick="togglePassword('current_password', 'icon_current')">
                                <i class="bi bi-eye" id="icon_current"></i>
                            </button>
                        </div>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- New Password -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" class="form-control bg-light border-0">
                                <button class="btn btn-light border-0 text-secondary" type="button" onclick="togglePassword('new_password', 'icon_new')">
                                    <i class="bi bi-eye" id="icon_new"></i>
                                </button>
                            </div>
                            @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control bg-light border-0">
                                <button class="btn btn-light border-0 text-secondary" type="button" onclick="togglePassword('new_password_confirmation', 'icon_confirm')">
                                    <i class="bi bi-eye" id="icon_confirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-lg text-white px-2 w-100 rounded-3 shadow-sm" 
                                style="background: linear-gradient(135deg, #405FA5, #60a5fa); font-size: 1.2rem;">
                            <i class="bi bi-save me-2"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
</script>

@endsection