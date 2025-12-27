<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | E-Commerce</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        /* --- SIDEBAR BASE --- */
        .sidebar {
            width: 280px;
            background: #285179ff;
            /* Pluto Dark Navy */
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            white-space: nowrap;
            /* Prevents text wrapping */
        }

        .sidebar a {
            color: #ffffffff;
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }

        .sidebar a i {
            font-size: 1.25rem;
            min-width: 30px;
            text-align: center;
        }

        .sidebar a:hover {
            color: #ffffffff;
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar a.active {
            color: #ffffffff;
            background: rgba(0, 0, 0, 0.2);
            border-left: 4px solid #ffffffff;
            padding-left: 21px;
        }

        /* --- MAIN CONTENT BASE --- */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 20px;
            width: calc(100% - 280px);
            transition: all 0.3s ease;
        }

        /* --- DESKTOP COLLAPSED STATE (PC) --- */
        body.sidebar-toggled .sidebar {
            width: 80px;
        }

        body.sidebar-toggled .sidebar .hide-on-collapse {
            display: none;
        }

        body.sidebar-toggled .sidebar .user-profile img {
            width: 40px;
            height: 40px;
        }

        /* Shrink avatar */
        body.sidebar-toggled .sidebar .user-profile {
            padding: 10px 0 !important;
        }

        body.sidebar-toggled .main-content {
            margin-left: 80px;
            width: calc(100% - 80px);
        }

        /* Adjust link centering when collapsed */
        body.sidebar-toggled .sidebar a {
            padding: 15px;
        }

        body.sidebar-toggled .sidebar a i {
            min-width: 0;
            margin: 0;
        }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar {
                left: -280px;
            }

            /* Hide by default */
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            /* When Toggled on Mobile: Show Sidebar fully */
            body.sidebar-toggled .sidebar {
                left: 0;
                width: 280px;
            }

            body.sidebar-toggled .sidebar .hide-on-collapse {
                display: block;
            }

            /* Show text */

            /* Overlay effect for content */
            body.sidebar-toggled .main-content {
                opacity: 0.5;
                pointer-events: none;
            }
        }


        /* ADMIN CUSTOM SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
            /* Thinner for Admin */
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #e9ecef;
        }

        ::-webkit-scrollbar-thumb {
            background: #285179;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1a3652;
        }
    </style>
</head>

<body>
    @include('partials.preloader')
    
    @include('admin.partials.sidebar')
    <main class="main-content">
        @include('admin.partials.header')

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')

    <script>
        // Global Toggle Function
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
        }
    </script>
</body>

</html>