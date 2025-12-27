<!-- 1. HERO SLIDER (News Style + Navigation) -->
@if(isset($sliders) && $sliders->count() > 0)
<section class="hero-slider-section mb-5 position-relative">
    <div class="container">
        
        <!-- Swiper -->
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                
                @foreach($sliders as $slider)
                <div class="swiper-slide h-auto">
                    <a href="{{ $slider->link ?? '#' }}" class="hero-card-link text-decoration-none d-block h-100">
                        <article class="hero-card h-100">
                            
                            <!-- Image -->
                            <div class="hero-image-box position-relative overflow-hidden">
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}" 
                                         class="w-100 h-100 object-fit-cover transition-transform" 
                                         alt="{{ $slider->title }}">
                                @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image fs-1 text-muted opacity-25"></i>
                                    </div>
                                @endif
                                
                                <!-- Dark Overlay -->
                                <div class="img-overlay"></div>
                            </div>

                            <!-- Content Overlay -->
                            <div class="hero-content-overlay position-absolute bottom-0 start-0 w-100 p-4 text-white">
                                <div class="content-inner">
                                    @if($slider->subtitle)
                                        <span class="badge bg-warning text-dark mb-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">
                                            {{ $slider->subtitle }}
                                        </span>
                                    @endif
                                    
                                    <h3 class="fw-bold mb-2 text-shadow">{{ $slider->title }}</h3>
                                    
                                </div>
                            </div>

                        </article>
                    </a>
                </div>
                @endforeach

            </div>
            
            <!-- Pagination Dots -->
            <div class="swiper-pagination hero-pagination"></div>

            <!-- ADDED: Navigation Buttons -->
            <div class="swiper-button-next hero-btn-next"></div>
            <div class="swiper-button-prev hero-btn-prev"></div>
        </div>

    </div>
</section>

<!-- STYLES -->
<style>
    /* Section Spacing */
    .hero-slider-section { padding-top: 20px; }

    /* Swiper Container */
    .hero-swiper { padding-bottom: 50px !important; }

    /* Card Styling */
    .hero-card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        background: #000;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    /* Image Box */
    .hero-image-box { height: 400px; }
    @media (max-width: 768px) { .hero-image-box { height: 250px; } }

    /* Overlay */
    .img-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
        pointer-events: none;
    }

    /* Content */
    .text-shadow { text-shadow: 0 2px 10px rgba(0,0,0,0.5); }
    .cta-link { color: #ffc107; transition: transform 0.3s; }
    .hero-card-link:hover .cta-link { transform: translateX(10px); color: #fff; }

    /* Pagination */
    .hero-pagination .swiper-pagination-bullet {
        width: 30px; height: 4px; border-radius: 2px;
        background: #ddd; opacity: 1; transition: 0.3s;
    }
    .hero-pagination .swiper-pagination-bullet-active { background: #285179; width: 45px; }

    /* --- NEW: Navigation Buttons Styling --- */
    .hero-btn-next, .hero-btn-prev {
        background-color: white;
        width: 50px; height: 50px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        opacity: 0; /* Hidden by default, show on hover */
    }
    
    /* Show buttons when hovering over the main section */
    .hero-swiper:hover .hero-btn-next, 
    .hero-swiper:hover .hero-btn-prev {
        opacity: 1;
    }

    /* Icon styling inside buttons */
    .hero-btn-next::after, .hero-btn-prev::after {
        font-size: 20px;
        font-weight: bold;
        color: #285179; /* Brand Blue */
    }

    .hero-btn-next:hover, .hero-btn-prev:hover {
        background-color: #285179;
        transform: scale(1.1);
    }

    .hero-btn-next:hover::after, .hero-btn-prev:hover::after {
        color: white;
    }

    /* Hide buttons on mobile to keep it clean (Swipe is better) */
    @media (max-width: 768px) {
        .hero-btn-next, .hero-btn-prev { display: none; }
    }
</style>

<!-- SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.hero-swiper')) {
            new Swiper('.hero-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.hero-pagination',
                    clickable: true,
                },
                // NEW: Navigation Config
                navigation: {
                    nextEl: '.hero-btn-next',
                    prevEl: '.hero-btn-prev',
                },
                breakpoints: {
                    0: { slidesPerView: 1, spaceBetween: 15 },
                    768: { slidesPerView: 1, spaceBetween: 20 }
                }
            });
        }
    });
</script>
@endif