<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Online Market</title>
    <!-- Local CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .auth-container {
            width: 100%;
            max-width: 900px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            display: flex;
            min-height: 600px;
        }

        /* Left Side (Banner) */
        .auth-banner {
            width: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        /* Decorative Circles */
        .circle {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .c1 { width: 200px; height: 200px; top: -50px; right: -50px; }
        .c2 { width: 300px; height: 300px; bottom: -100px; left: -100px; }

        /* Right Side (Forms) */
        .auth-form-side {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Form Styling */
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #e1e1e1;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
            background-color: #fff;
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1px solid #e1e1e1;
            background: #fff;
            color: #6c757d;
        }
        
        .btn-primary {
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        /* Tabs (Toggle) */
        .auth-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            background: #f1f1f1;
            padding: 5px;
            border-radius: 15px;
        }
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            border-radius: 12px;
            font-weight: 600;
            color: #666;
            transition: 0.3s;
        }
        .auth-tab.active {
            background: white;
            color: #0d6efd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-container { flex-direction: column; max-width: 90%; min-height: auto; }
            .auth-banner { display: none; } /* Hide image on mobile */
            .auth-form-side { width: 100%; padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        
        <!-- LEFT: Banner Area -->
        <div class="auth-banner">
            <div class="circle c1"></div>
            <div class="circle c2"></div>
            
            <div style="z-index: 2;">
                <h1 class="fw-bold display-5 mb-3">Welcome Back!</h1>
                <p class="lead mb-4" style="opacity: 0.9;">
                    Discover the best products at unbeatable prices. Join our community today.
                </p>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 p-3 rounded-3 text-center">
                        <h4 class="fw-bold mb-0">10k+</h4>
                        <small>Products</small>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3 text-center">
                        <h4 class="fw-bold mb-0">5k+</h4>
                        <small>Users</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Forms Area -->
        <div class="auth-form-side">
            
            <!-- Logo Mobile Only -->
            <div class="text-center d-md-none mb-4">
                <h3 class="fw-bold text-primary">ONLINE MARKET</h3>
            </div>

            <!-- Toggle Buttons -->
            <div class="auth-tabs">
                <div class="auth-tab active" onclick="switchTab('login')">Login</div>
                <div class="auth-tab" onclick="switchTab('register')">Register</div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger py-2 rounded-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- LOGIN FORM -->
            <form id="loginForm" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <h3 class="fw-bold">Sign In</h3>
                    <p class="text-muted small">Enter your credentials to access your account.</p>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="small text-decoration-none text-primary">Forgot password?</a>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login to Account</button>
                </div>
            </form>

            <!-- REGISTER FORM (Hidden by default) -->
            <form id="registerForm" action="{{ route('register') }}" method="POST" style="display: none;">
                @csrf
                <div class="mb-4">
                    <h3 class="fw-bold">Create Account</h3>
                    <p class="text-muted small">Fill in the details to get started.</p>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Full Name" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Password (Min 8 chars)" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Confirm Password" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register Now</button>
                </div>
            </form>

        </div>
    </div>

    <!-- JavaScript to toggle tabs -->
    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const tabs = document.querySelectorAll('.auth-tab');

            if (tab === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                tabs[0].classList.remove('active');
                tabs[1].classList.add('active');
            }
        }
    </script>

</body>
</html>