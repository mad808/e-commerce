<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Market')</title>

    <!-- LOCAL CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">

    @livewireStyles

    <style>
        :root {
            --brand-blue: #285179ff;
            --brand-green: #198754;
            --brand-orange: #fd7e14;
        }

        body {
            background-color: #f8f9fa;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar & Footer Backgrounds */
        .bg-brand {
            background-color: var(--brand-blue) !important;
            color: white;
        }

        /* Navbar Tweaks */
        .navbar-custom {
            background-color: var(--brand-blue);
            box-shadow: 0 4px 10px #285179ff;
            ;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        /* Footer Tweaks */
        .footer {
            background-color: var(--brand-blue);
            color: #e0e0e0;
            margin-top: auto;
        }

        .footer a {
            color: #e0e0e0;
            text-decoration: none;
        }

        .footer a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .footer-bottom {
            background-color: rgba(0, 0, 0, 0.1);
        }

        /* Button Overrides */
        .btn-cart {
            background-color: var(--brand-green);
            border-color: var(--brand-green);
            color: white;
        }

        .btn-cart:hover {
            background-color: #146c43;
            color: white;
        }

        .text-orange {
            color: var(--brand-orange) !important;
        }

        /* Utilities */
        .hover-lift {
            transition: transform 0.3s;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        .product-card {
            transition: box-shadow 0.3s;
        }

        .product-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .cart-badge {
            font-size: 0.7rem;
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -20%);
        }

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        /* Track (Background) */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle (The moving part) */
        ::-webkit-scrollbar-thumb {
            background: #285179;
            /* YOUR COLOR */
            border-radius: 5px;
        }

        /* Handle on Hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #1a3652;
            /* Slightly darker for effect */
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    @include('partials.preloader')

    <!-- INCLUDE NAVBAR -->
    @include('partials.navbar')

    <!-- MAIN CONTENT -->
    <main class="py-4 flex-grow-1">
        @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <!-- Standard Views -->
        @yield('content')

        <!-- Livewire Components (CRITICAL LINE) -->
        {{ $slot ?? '' }}
        
        @include('partials.news')
    </main>


    <!-- INCLUDE FOOTER -->
    @include('partials.footer')

    @include('client.partials.location_modal')

    <livewire:product-modal />

    <!-- LOCAL JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>

    @livewireScripts
</body>

</html>