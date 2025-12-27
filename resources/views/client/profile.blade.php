@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">

        <!-- LEFT SIDEBAR -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <!-- User Info Mini -->
                    <div class="p-4 text-center bg-brand text-white">
                        <div class="mx-auto bg-white text-brand rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm"
                            style="width: 70px; height: 70px; font-size: 1.8rem; font-weight: bold;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                        <small class="opacity-75">{{ Auth::user()->email }}</small>
                    </div>

                    <!-- Navigation -->
                    <div class="list-group list-group-flush py-2">
                        <a href="{{ route('client.profile') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 active-menu">
                            <i class="bi bi-person-circle me-2"></i> My Profile
                        </a>
                        <a href="{{ route('client.orders.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                            <i class="bi bi-box-seam me-2"></i> My Orders
                        </a>
                        <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                            <i class="bi bi-heart me-2"></i> Wishlist
                        </a>
                        <a href="{{ route('contact.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3">
                            <i class="bi bi-chat-dots me-2"></i> Support
                        </a>

                        <div class="border-top my-2"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="list-group-item list-group-item-action border-0 px-4 py-3 text-danger fw-bold">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold m-0" style="color: #285179;">Edit Profile</h5>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('client.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted small fw-bold mb-3">Personal Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->email }}" readonly disabled title="Email cannot be changed">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->phone }}" placeholder="+993 6X XX XX XX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Delivery Address</label>
                                <input type="text" name="address" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->address }}" placeholder="City, Street, House...">
                            </div>

                            <div class="col-12">
                                <hr class="text-muted opacity-25">
                            </div>

                            <!-- Security -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted small fw-bold mb-3">Change Password <small class="fw-normal text-muted">(Leave empty to keep current)</small></h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Current Password</label>
                                <input type="password" name="current_password" class="form-control bg-light border-0 py-2">
                                @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">New Password</label>
                                <input type="password" name="new_password" class="form-control bg-light border-0 py-2">
                                @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control bg-light border-0 py-2">
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-brand rounded-pill px-5 py-2 fw-bold shadow-sm">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .bg-brand {
        background-color: #285179;
        color: white;
    }

    .text-brand {
        color: #285179;
    }

    .list-group-item {
        font-size: 0.95rem;
        color: #555;
        transition: 0.2s;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        color: #285179;
    }

    .active-menu {
        background-color: #f0f4f8 !important;
        color: #285179 !important;
        font-weight: 700;
        border-left: 4px solid #285179 !important;
    }

    .form-control:focus {
        box-shadow: none;
        border: 1px solid #285179 !important;
        background-color: #fff !important;
    }
</style>
@endsection