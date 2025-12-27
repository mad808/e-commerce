<li class="nav-item position-relative d-none d-lg-block">
    <a class="nav-icon-link" href="{{ route('cart.index') }}" title="My Cart">
        <i class="bi bi-bag"></i>
        @if($count > 0)
            <span class="cart-badge pulse-animation">{{ $count }}</span>
        @endif
    </a>
</li>