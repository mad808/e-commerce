@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container text-center py-5">
    <div class="mb-4">
        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width: 100px; height: 100px;">
            <i class="bi bi-check-lg display-3"></i>
        </div>
    </div>

    <h1 class="fw-bold mb-3">Thank You for Your Order!</h1>
    <p class="lead text-muted mb-5">
        Your order has been placed successfully. We will contact you soon for delivery.
    </p>

    <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('home') }}" class="btn btn-outline-dark px-4">Return Home</a>
        <a href="{{ route('client.orders.index') }}" class="btn btn-primary px-4">View My Orders</a>
    </div>
</div>
@endsection