<!-- ========================================================= -->
<!-- 1. TOP UTILITY BAR (Desktop Only) -->
<!-- ========================================================= -->
<div class="py-1 border-bottom d-none d-lg-block text-muted small">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Left: Location & Date -->
        <div class="d-flex align-items-center gap-4">
            <!-- Location (Modal Trigger) -->
            <a href="#" class="text-decoration-none text-muted d-flex align-items-center gap-1 hover-blue" data-bs-toggle="modal" data-bs-target="#locationModal">
                <i class="bi bi-geo-alt-fill text-blue"></i>
                <span class="fw-bold">{{ session('location_name', 'Select Location') }}</span>
            </a>

            <!-- Date -->
            <div class="d-flex align-items-center gap-1 border-start ps-3 ms-3">
                <i class="bi bi-calendar-event text-blue"></i>
                <span>{{ now()->format('d F Y') }}</span>
            </div>
        </div>

        <!-- Right: Language & Auth -->
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <a href="#" class="text-muted text-decoration-none dropdown-toggle text-uppercase fw-bold" data-bs-toggle="dropdown">
                    @php
                    $locale = session('locale', 'en');
                    $flags = [
                    'en' => '🇬🇧 English',
                    'tm' => '🇹🇲 Türkmen',
                    'ru' => '🇷🇺 Русский'
                    ];
                    @endphp
                    {{ $flags[$locale] }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('locale.switch', 'tm') }}">
                            <span>🇹🇲</span> Türkmen
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('locale.switch', 'en') }}">
                            <span>🇬🇧</span> English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('locale.switch', 'ru') }}">
                            <span>🇷🇺</span> Русский
                        </a>
                    </li>
                </ul>
            </div>

            <span class="text-muted opacity-25">|</span>

            @auth
            <span class="text-brand fw-bold border-start ps-3">Hi, {{ Str::limit(Auth::user()->name, 10) }}</span>
            @else
            <a href="{{ route('login') }}" class="text-decoration-none text-brand fw-bold border-start ps-3">
                <i class="bi bi-person me-1"></i> Login / Register
            </a>
            @endauth
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- 2. MAIN NAVBAR (Trendyol Style) -->
<!-- ========================================================= -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2">
    <div class="container">

        <!-- A. LOGO -->
        <a class="navbar-brand fw-bold me-lg-5" href="{{ route('home') }}" style="font-size: 1.5rem; letter-spacing: -0.5px;">
            ONLINE MARKET
        </a>

        <!-- B. MOBILE TOGGLER -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- C. COLLAPSIBLE AREA -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- 1. BIG SEARCH BAR (Centered, Trendyol Style) -->
            <div class="flex-grow-1 mx-lg-4 my-3 my-lg-0 position-relative">
                <form action="{{ route('shop') }}" method="GET" class="search-form">
                    <div class="input-group trendyol-search">
                        <input class="form-control border-0 ps-4 py-2"
                            id="searchInput"
                            type="search"
                            name="q"
                            autocomplete="off"
                            placeholder="Търси по категория, артикул или марка"
                            value="{{ request('q') }}">
                        <button class="btn border-0 pe-3" type="submit">
                            <i class="bi bi-search fs-5 text-blue"></i>
                        </button>
                    </div>
                </form>
                <!-- AJAX Results -->
                <div id="searchResults" class="dropdown-menu w-100 shadow-lg border-0 rounded-3 mt-1 overflow-hidden p-0" style="display: none; position: absolute; z-index: 1050;"></div>
            </div>

            <!-- 2. RIGHT ICON MENU (Minimal, Icon Only on Desktop) -->
            <ul class="navbar-nav align-items-lg-center gap-lg-3 ms-auto">

                <!-- Customer Service -->
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link text-dark p-2" href="{{ route('contact.index') }}" title="Customer Service">
                        <i class="bi bi-headset fs-4"></i>
                    </a>
                </li>

                <!-- Account -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark p-2 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" title="Account">
                        <i class="bi bi-person fs-4"></i>
                        <span class="d-lg-none">Account</span>
                    </a>
                    <!-- User Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2">
                        @auth
                        <li class="px-3 py-2 border-bottom mb-2 bg-light rounded">
                            <small class="text-muted d-block">Signed in as</small>
                            <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                        </li>
                        @if(Auth::user()->role === 'admin')
                        <li><a class="dropdown-item rounded" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Admin</a></li>
                        @endif
                        <li><a class="dropdown-item rounded" href="{{ route('client.orders.index') }}"><i class="bi bi-box-seam me-2"></i> Orders</a></li>
                        <li><a class="dropdown-item rounded" href="{{ route('client.profile') }}"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger fw-bold rounded"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                        @else
                        <li><a class="dropdown-item fw-bold rounded" href="{{ route('login') }}">Login</a></li>
                        <li><a class="dropdown-item rounded" href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </li>

                <!-- Favorites -->
                <li class="nav-item">
                    <a class="nav-link text-dark p-2 position-relative" href="{{ route('favorites.index') }}" title="Favorites">
                        <i class="bi bi-heart fs-4"></i>
                        <span class="d-lg-none ms-2">Favorites</span>
                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item">
                    <a class="nav-link text-dark p-2 position-relative" href="{{ route('cart.index') }}" title="Cart">
                        <i class="bi bi-bag fs-4"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                        <span class="cart-badge-simple">{{ $cartCount }}</span>
                        @endif
                        <span class="d-lg-none ms-2">Cart</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark p-2 position-relative" href="{{ route('shop') }}" title="Filter">
                        <i class="bi bi-sliders2-vertical fs-4"></i>
                        <span class="d-lg-none ms-2">Filter</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>


<!-- ========================================================= -->
<!-- STYLES -->
<!-- ========================================================= -->
<style>
    :root {
        --brand-blue: #285179;
    }

    /* Top Bar */
    .text-blue {
        color: var(--brand-blue) !important;
    }

    .text-brand {
        color: var(--brand-blue) !important;
    }

    .hover-blue:hover span {
        color: var(--brand-blue);
        transition: 0.2s;
    }

    /* Main Navbar - Clean White Background */
    .navbar {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    /* Logo Styling */
    .navbar-brand {
        color: var(--brand-blue) !important;
        font-weight: 700;
    }

    /* Trendyol Search Bar - Rounded with Light Gray Background */
    .trendyol-search {
        background: #f3f3f3;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e6e6e6;
        transition: all 0.3s ease;
    }

    .trendyol-search:focus-within {
        background: white;
        border-color: var(--brand-blue);
        box-shadow: 0 0 0 3px rgba(40, 81, 121, 0.1);
    }

    .search-form input {
        font-size: 0.9rem;
        background: transparent !important;
    }

    .search-form input:focus {
        box-shadow: none;
        background: transparent !important;
    }

    .search-form input::placeholder {
        color: #999;
    }

    /* Nav Links - Minimal Icon Style */
    .navbar-nav .nav-link {
        color: #333 !important;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .navbar-nav .nav-link:hover {
        color: var(--brand-blue) !important;
    }

    /* Cart Badge - Simple Circle */
    .cart-badge-simple {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--brand-blue);
        color: white;
        font-size: 0.65rem;
        font-weight: 700;
        min-width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    /* Category Navigation */
    .nav-link {
        color: #333;
        transition: color 0.2s;
        font-size: 0.95rem;
    }

    .nav-link:hover {
        color: var(--brand-blue);
    }

    .fw-500 {
        font-weight: 500;
    }

    /* Mobile Category Nav - Horizontal Scroll */
    .mobile-category-nav {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .mobile-category-nav::-webkit-scrollbar {
        display: none;
    }

    /* Dropdown Improvements */
    .dropdown-item {
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Mobile Styles */
    @media (max-width: 991px) {
        .navbar-collapse {
            background: white;
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            justify-content: flex-start;
        }

        .navbar-nav .nav-link:last-child {
            border-bottom: none;
        }
    }

    /* Focus States */
    .navbar-toggler:focus {
        box-shadow: none;
    }

    /* Language dropdown visibility */
    .dropdown-menu {
        z-index: 2000 !important;
    }

    /* Ensure top utility bar allows dropdown overflow */
    .py-1.border-bottom {
        position: relative;
        overflow: visible !important;
    }

    #searchResults {
        z-index: 9999 !important;
        max-height: 400px;
        overflow-y: auto;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>

<!-- ========================================================= -->
<!-- SCRIPTS (Preserved Exact Functionality) -->
<!-- ========================================================= -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('searchInput');
        const resultsBox = document.getElementById('searchResults');
        let timeout = null;

        if (input && resultsBox) {
            console.log("Search Script Loaded & Elements Found");

            // 1. TYPING EFFECT (Visual Only)
            const phrases = ["Search products...", "Find brands...", "Type here..."];
            let phraseIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            function typeEffect() {
                if (document.activeElement === input && input.value.length > 0) return;
                const current = phrases[phraseIndex];

                if (isDeleting) {
                    input.setAttribute('placeholder', current.substring(0, charIndex - 1));
                    charIndex--;
                } else {
                    input.setAttribute('placeholder', current.substring(0, charIndex + 1));
                    charIndex++;
                }

                if (!isDeleting && charIndex === current.length) {
                    isDeleting = true;
                    setTimeout(typeEffect, 2000);
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    setTimeout(typeEffect, 500);
                } else {
                    setTimeout(typeEffect, isDeleting ? 50 : 100);
                }
            }
            typeEffect();

            // 2. LIVE SEARCH LOGIC
            input.addEventListener('keyup', function() {
                clearTimeout(timeout);
                const query = this.value.trim();

                // Hide if empty or too short
                if (query.length < 2) {
                    resultsBox.style.display = 'none';
                    return;
                }

                // Debounce to prevent too many requests
                timeout = setTimeout(() => {
                    console.log("Searching for:", query); // Debug Log

                    // Use the route helper to generate URL
                    const searchUrl = "{{ route('search.ajax') }}";

                    fetch(`${searchUrl}?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Results found:", data.length); // Debug Log
                            let html = '';

                            if (data.length > 0) {
                                html += '<div class="list-group list-group-flush">';

                                data.forEach(product => {
                                    // Handle Image Path correctly
                                    let imgPath = product.image ?
                                        `/storage/${product.image}` :
                                        'https://placehold.co/50?text=No+Img';

                                    // Format Price
                                    let price = new Intl.NumberFormat('en-US', {
                                        minimumFractionDigits: 2
                                    }).format(product.price);

                                    // Build HTML Item
                                    html += `
                                        <a href="/product/${product.slug}" class="list-group-item list-group-item-action d-flex align-items-center p-2 gap-3" style="transition:0.2s;">
                                            <img src="${imgPath}" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                            <div class="flex-grow-1" style="line-height:1.2;">
                                                <h6 class="mb-0 text-dark fw-bold small text-truncate" style="max-width: 180px;">${product.name}</h6>
                                                <small class="text-muted" style="font-size: 0.7rem;">${product.category ? product.category.name : 'Item'}</small>
                                            </div>
                                            <span class="fw-bold text-primary small">$${price}</span>
                                        </a>`;
                                });

                                // View All Link
                                html += `
                                    <a href="{{ route('shop') }}?q=${query}" class="list-group-item list-group-item-action text-center text-primary fw-bold bg-light small py-2">
                                        View All Results <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>`;

                                resultsBox.innerHTML = html;
                                resultsBox.style.display = 'block';
                            } else {
                                resultsBox.innerHTML = `<div class="p-3 text-center text-muted small"><i class="bi bi-search"></i> No products found.</div>`;
                                resultsBox.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Search Error:', error);
                            resultsBox.style.display = 'none';
                        });
                }, 300); // 300ms delay
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
                    resultsBox.style.display = 'none';
                }
            });
        }
    });
</script>