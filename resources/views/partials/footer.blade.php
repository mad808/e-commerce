<footer class="footer bg-white border-top mt-5">
    <div class="container py-5">
        <div class="row g-4">
            
            <!-- About Us Section -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3" style="color: #285179;">ONLINE MARKET</h5>
                <p class="text-muted small mb-3">
                    {{ $globalSettings['footer_text'] ?? 'We provide the best products at the best prices. Fast delivery and secure payments.' }}
                </p>
                
                <!-- Social Links -->
                <div class="d-flex gap-2">
                    @if(!empty($globalSettings['instagram']))
                    <a href="{{ $globalSettings['instagram'] }}" class="social-link" target="_blank" rel="noopener">
                        <i class="bi bi-instagram"></i>
                    </a>
                    @endif
                    
                    @if(!empty($globalSettings['facebook']))
                    <a href="{{ $globalSettings['facebook'] }}" class="social-link" target="_blank" rel="noopener">
                        <i class="bi bi-facebook"></i>
                    </a>
                    @endif
                    
                    @if(!empty($globalSettings['telegram']))
                    <a href="{{ $globalSettings['telegram'] }}" class="social-link" target="_blank" rel="noopener">
                        <i class="bi bi-telegram"></i>
                    </a>
                    @endif
                    
                    @if(!empty($globalSettings['tiktok']))
                    <a href="{{ $globalSettings['tiktok'] }}" class="social-link" target="_blank" rel="noopener">
                        <i class="bi bi-tiktok"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-dark">Quick Links</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('shop') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Shop
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('news.index') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>News & Articles
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact.index') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Contact Us
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-dark">Customer Service</h6>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>My Account
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('cart.index') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Shopping Cart
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('favorites.index') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Wishlist
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('client.orders.index') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-chevron-right small me-1"></i>Order Tracking
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3 text-dark">Contact Info</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-telephone-fill text-primary me-2 mt-1"></i>
                        <div>
                            <small class="text-muted d-block">Phone</small>
                            <a href="tel:{{ $globalSettings['phone'] ?? '+993 65 00 00 00' }}" class="text-dark text-decoration-none fw-500">
                                {{ $globalSettings['phone'] ?? '+993 65 00 00 00' }}
                            </a>
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-envelope-fill text-primary me-2 mt-1"></i>
                        <div>
                            <small class="text-muted d-block">Email</small>
                            <a href="mailto:{{ $globalSettings['email'] ?? 'info@market.com' }}" class="text-dark text-decoration-none fw-500">
                                {{ $globalSettings['email'] ?? 'info@market.com' }}
                            </a>
                        </div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                        <div>
                            <small class="text-muted d-block">Address</small>
                            <span class="text-dark fw-500">{{ $globalSettings['address'] ?? 'Ashgabat, Turkmenistan' }}</span>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom border-top py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <small class="text-muted">
                        &copy; {{ date('Y') }} <strong style="color: #285179;">Online Market</strong>. All Rights Reserved.
                    </small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-muted">
                        Made with <i class="bi bi-heart-fill text-danger"></i> in Turkmenistan
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Styles */
    .footer {
        background-color: #ffffff;
        margin-top: auto;
    }

    /* Social Links - Royal Blue Circles */
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: #ffffffff;
        border-radius: 50%;
        color: #285179;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .social-link:hover {
        background: #285179;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(40, 81, 121, 0.3);
    }

    /* Footer Links */
    .footer-links li a {
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: inline-block;
    }
    .footer-links li a:hover {
        color: #285179 !important;
        transform: translateX(5px);
    }
    .footer-links li a i {
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .footer-links li a:hover i {
        opacity: 1;
    }

    /* Contact Info Icons */
    .footer .bi-telephone-fill,
    .footer .bi-envelope-fill,
    .footer .bi-geo-alt-fill {
        color: #285179 !important;
    }

    /* Footer Bottom */
    .footer-bottom {
        background-color: #f8f9fa;
    }

    /* Utility Class */
    .fw-500 {
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .footer .col-lg-2,
        .footer .col-lg-3,
        .footer .col-lg-4 {
            margin-bottom: 2rem;
        }
        .footer-bottom .row > div {
            text-align: center !important;
        }
    }
</style>