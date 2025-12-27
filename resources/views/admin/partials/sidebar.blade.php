<aside class="sidebar">
    <div class="px-4 py-3 text-center text-uppercase small fw-bold text-light hide-on-collapse" style="letter-spacing: 1px;">
        ADMIN PANEL
    </div>

    <nav class="flex-grow-1 overflow-auto custom-scrollbar">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span class="hide-on-collapse ms-3">Dashboard</span>
        </a>

        @if(auth()->user()->hasPermission('orders'))
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check"></i> <span class="hide-on-collapse ms-3">Orders</span>
            </a>
            
            <a href="{{ route('admin.financial.index') }}" class="{{ request()->routeIs('admin.financial.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> <span class="hide-on-collapse ms-3">Finances</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('products'))
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> <span class="hide-on-collapse ms-3">Products</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> <span class="hide-on-collapse ms-3">Categories</span>
            </a>

            <a href="{{ route('admin.attributes.index') }}" class="{{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> <span class="hide-on-collapse ms-3">Attributes</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('sliders'))
            <a href="{{ route('admin.sliders.index') }}" class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="bi bi-images"></i> <span class="hide-on-collapse ms-3">Banners</span>
            </a>

            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> <span class="hide-on-collapse ms-3">News</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('locations'))
            <a href="{{ route('admin.locations.index') }}" class="{{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> <span class="hide-on-collapse ms-3">Locations</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('users'))
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span class="hide-on-collapse ms-3">Clients</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('messages'))
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="bi bi-envelope"></i> <span class="hide-on-collapse ms-3">Messaging</span>
            </a>
        @endif

        @if(auth()->user()->hasPermission('settings'))
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> <span class="hide-on-collapse ms-3">Settings</span>
            </a>
        @endif
    </nav>
</aside>