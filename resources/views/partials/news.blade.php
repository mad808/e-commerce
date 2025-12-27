@if(isset($latestNews) && $latestNews->count())
<section class="latest-news-section py-3 mt-3 position-relative">
    <div class="container">

        <!-- Header -->
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="title-icon">📰</div>
                    <h3 class="section-title mb-0">Soňky Habarlar</h3>
                </div>
            </div>
            <div class="col-md-4 text-md-end d-none d-md-block">
                <a href="{{ route('news.index') }}" class="view-all-link">
                    Ählisini gör <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        </div>

        <!-- Swiper -->
        <div class="swiper news-swiper">
            <div class="swiper-wrapper">

                @foreach($latestNews as $news)
                <div class="swiper-slide">
                    <a href="{{ route('news.show', $news) }}" class="news-card-link">
                        <article class="news-card">

                            <!-- Image -->
                            <div class="news-image">
                                @if($news->image)
                                <img src="{{ asset('storage/'.$news->image) }}" alt="{{ $news->title }}" loading="lazy">
                                @else
                                <div class="image-placeholder">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                                @endif
                                
                                <!-- Gradient Overlay -->
                                <div class="image-overlay"></div>
                            </div>

                            <!-- Content -->
                            <div class="news-content">
                                <!-- Date & Views -->
                                <div class="news-meta">
                                    <span class="meta-item">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $news->created_at->format('d M') }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="bi bi-eye"></i>
                                        {{ number_format($news->views) }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h6 class="news-title">{{ Str::limit($news->title, 50) }}</h6>
                                
                                <!-- Summary -->
                                <p class="news-summary">{{ Str::limit(strip_tags($news->summary ?? $news->body), 55) }}</p>

                                <!-- Read More -->
                                <div class="read-more-btn">
                                    <span>Okamak</span>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </article>
                    </a>
                </div>
                @endforeach

            </div>

            <!-- Navigation Arrows -->
            <!-- <div class="swiper-button-prev news-btn-prev"></div>
            <div class="swiper-button-next news-btn-next"></div> -->
            
            <!-- Pagination -->
            <div class="swiper-pagination news-pagination"></div>
        </div>

        <!-- Mobile Button -->
        <div class="d-md-none mt-3">
            <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                <i class="bi bi-grid-3x3-gap"></i> Ählisini gör
            </a>
        </div>

    </div>
</section>

<style>
    .latest-news-section {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        position: relative;
    }

    .latest-news-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(40, 81, 121, 0.1), transparent);
    }

    /* Header Styling */
    .title-icon {
        font-size: 1.8rem;
        margin-right: 10px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .section-title {
        font-weight: 800;
        color: #285179;
        font-size: 1.4rem;
        letter-spacing: -0.5px;
    }

    .view-all-link {
        font-weight: 600;
        color: #285179;
        text-decoration: none;
        padding: 6px 15px;
        border-radius: 20px;
        background: rgba(40, 81, 121, 0.05);
        transition: all 0.3s ease;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .view-all-link:hover {
        background: #285179;
        color: white;
        transform: translateX(3px);
    }

    /* Swiper Container */
    .news-swiper {
        padding: 5px 5px 40px 5px;
        position: relative;
    }

    /* Card Container */
    .news-card-link {
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .news-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .news-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #285179, #2c8bea);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .news-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(40, 81, 121, 0.15);
        border-color: rgba(40, 81, 121, 0.2);
    }

    .news-card:hover::before {
        transform: scaleX(1);
    }

    /* Image Section - THINNER */
    .news-image {
        height: 130px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .news-card:hover .news-image img {
        transform: scale(1.1) rotate(1deg);
    }

    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .news-card:hover .image-overlay {
        opacity: 1;
    }

    .image-placeholder {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: rgba(255, 255, 255, 0.6);
    }

    /* Content Section - COMPACT */
    .news-content {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Meta Info */
    .news-meta {
        display: flex;
        gap: 12px;
        font-size: 0.7rem;
        color: #6c757d;
        font-weight: 500;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-item i {
        font-size: 0.8rem;
        color: #285179;
    }

    /* Title */
    .news-title {
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
        font-size: 0.9rem;
        color: #1a202c;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    .news-card:hover .news-title {
        color: #285179;
    }

    /* Summary */
    .news-summary {
        font-size: 0.75rem;
        color: #718096;
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Read More Button */
    .read-more-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.7rem;
        font-weight: 700;
        color: #285179;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: auto;
        padding-top: 8px;
        border-top: 1px solid #f0f0f0;
        transition: all 0.3s ease;
    }

    .read-more-btn i {
        transition: transform 0.3s ease;
    }

    .news-card:hover .read-more-btn {
        color: #2c8bea;
    }

    .news-card:hover .read-more-btn i {
        transform: translateX(5px);
    }

    /* Navigation Arrows */
    .news-btn-prev,
    .news-btn-next {
        width: 35px;
        height: 35px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .news-btn-prev:after,
    .news-btn-next:after {
        font-size: 16px;
        font-weight: 900;
        color: #285179;
    }

    .news-btn-prev:hover,
    .news-btn-next:hover {
        background: #285179;
        transform: scale(1.1);
    }

    .news-btn-prev:hover:after,
    .news-btn-next:hover:after {
        color: white;
    }

    /* Pagination Dots */
    .news-pagination {
        bottom: 5px !important;
    }

    .news-pagination .swiper-pagination-bullet {
        width: 25px;
        height: 4px;
        border-radius: 2px;
        background: #cbd5e0;
        opacity: 1;
        margin: 0 4px !important;
        transition: all 0.3s ease;
    }

    .news-pagination .swiper-pagination-bullet-active {
        background: linear-gradient(90deg, #285179, #2c8bea);
        width: 35px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.2rem;
        }

        .title-icon {
            font-size: 1.5rem;
        }

        .news-image {
            height: 120px;
        }

        .news-content {
            padding: 10px;
        }

        .news-btn-prev,
        .news-btn-next {
            display: none;
        }
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .news-card.loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Swiper('.news-swiper', {
            loop: true,
            speed: 600,
            autoplay: {
                delay: 4500,
                pauseOnMouseEnter: true,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.news-btn-next',
                prevEl: '.news-btn-prev',
            },
            pagination: {
                el: '.news-pagination',
                clickable: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.15,
                    spaceBetween: 12,
                    centeredSlides: true,
                },
                480: {
                    slidesPerView: 1.5,
                    spaceBetween: 12,
                    centeredSlides: false,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 2.5,
                    spaceBetween: 15,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                }
            },
            on: {
                init: function() {
                    // Add entrance animation
                    setTimeout(() => {
                        document.querySelectorAll('.news-card').forEach((card, index) => {
                            card.style.animation = `fadeInUp 0.6s ease-out ${index * 0.1}s forwards`;
                        });
                    }, 100);
                }
            }
        });
    });

    // Add fade-in animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endif