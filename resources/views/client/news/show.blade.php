@extends('layouts.app')

@section('title', $news->title)

@section('content')
<div id="reading-progress" class="fixed-top" style="height: 4px; background: #285179; width: 0%; z-index: 10000; transition: width 0.1s ease;"></div>

<div class="container py-4 py-lg-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Baş sahypa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-muted text-decoration-none">Habarlar</a></li>
            <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Makala</li>
        </ol>
    </nav>

    <div class="row g-lg-5">
        <div class="col-lg-8">
            <article>
                <header class="mb-5">
                    <div class="row g-4 align-items-center">
                        @if($news->image)
                        <div class="col-md-5">
                            <div class="rounded-4 overflow-hidden shadow-sm article-hero-container">
                                <img src="{{ asset('storage/' . $news->image) }}" class="w-100 h-100 article-hero-img" alt="{{ $news->title }}">
                            </div>
                        </div>
                        @endif

                        <div class="{{ $news->image ? 'col-md-7' : 'col-12' }}">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="badge rounded-pill px-3 py-2 bg-primary-soft text-primary small fw-bold">Habarlar</span>
                                <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ $news->created_at->format('d.m.Y') }}</span>
                            </div>
                            <h1 class="display-6 fw-extrabold text-dark mb-3 editorial-title">
                                {{ $news->title }}
                            </h1>
                            <div class="d-flex align-items-center gap-4 text-muted small">
                                <span><i class="bi bi-eye me-1"></i> {{ number_format($news->views) }} görüldi</span>
                                <span><i class="bi bi-clock-history me-1"></i> {{ ceil(str_word_count($news->body) / 200) }} min okama</span>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="article-content pe-lg-4">
                    @if($news->summary)
                    <div class="summary-box p-4 rounded-4 mb-5 shadow-sm">
                        <p class="lead mb-0 text-secondary italic">
                            <i class="bi bi-quote fs-2 text-primary opacity-25 d-block mb-1"></i>
                            "{{ $news->summary }}"
                        </p>
                    </div>
                    @endif

                    @if($news->video_url)
                    <div class="video-card-wrapper mb-5">
                        <div class="video-container shadow-lg rounded-4 overflow-hidden position-relative">
                            <div class="video-badge">
                                <i class="bi bi-play-circle-fill me-1"></i> Wideo habar
                            </div>

                            @if(Str::contains($news->video_url, ['youtube.com', 'youtu.be']))
                            @php
                            $videoId = current(explode('&', last(explode('/', str_replace('watch?v=', '', $news->video_url)))));
                            @endphp
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
                                    allowfullscreen
                                    class="border-0"></iframe>
                            </div>
                            @else
                            <div class="local-video-bg">
                                <video controls playsinline class="w-100 d-block player-custom">
                                    <source src="{{ asset('storage/' . $news->video_url) }}" type="video/mp4">
                                    Brauzeriňiz wideony goldamaýar.
                                </video>
                            </div>
                            @endif
                        </div>
                        <div class="video-caption mt-2 text-muted small text-center italic">
                            <i class="bi bi-info-circle me-1"></i> Wideony pleýer arkaly görüp bilersiňiz.
                        </div>
                    </div>
                    @endif

                    <div class="rich-text-body mb-5">
                        {!! nl2br(e($news->body)) !!}
                    </div>

                    <div class="share-footer py-4 border-top border-bottom d-flex flex-wrap justify-content-between align-items-center mb-5">
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-bold small text-uppercase tracking-wider">Paýlaşyň:</span>
                            <div class="d-flex gap-2">
                                <a href="https://t.me/share/url?url={{ urlencode(route('news.show', $news)) }}" target="_blank" class="share-icon-btn tg"><i class="bi bi-telegram"></i></a>
                                <a href="https://wa.me/?text={{ urlencode(route('news.show', $news)) }}" target="_blank" class="share-icon-btn wa"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </div>
                        <a href="{{ route('news.index') }}" class="btn btn-link text-decoration-none text-primary fw-bold p-0">
                            <i class="bi bi-arrow-left me-1"></i> Ähli habarlar
                        </a>
                    </div>
                </div>
            </article>

            <section class="related-news-section mb-5">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h3 class="fw-bold mb-0">Meňzeş habarlar</h3>
                    <a href="{{ route('news.index') }}" class="text-muted small text-decoration-none hover-primary">Hemmesini gör <i class="bi bi-chevron-right ms-1"></i></a>
                </div>
                <div class="row g-4">
                    @foreach($recentNews->take(3) as $related)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 related-card overflow-hidden">
                            <a href="{{ route('news.show', $related) }}" class="stretched-link"></a>
                            <div class="related-img-wrapper">
                                <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->title }}">
                                <div class="img-overlay"></div>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="card-title text-dark fw-bold small mb-2 lh-base line-clamp-2">{{ $related->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="text-muted extra-small">{{ $related->created_at->format('d.m.Y') }}</span>
                                    <span class="text-primary fw-bold extra-small">Okamak <i class="bi bi-arrow-right small"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="col-lg-4">
            <aside class="sidebar-sticky">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden widget-card">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h6 class="fw-bold text-dark border-start border-primary border-4 ps-3">Soňky habarlar</h6>
                    </div>
                    <div class="card-body p-4 pt-3">
                        <div class="d-flex flex-column gap-3">
                            @foreach($recentNews as $recent)
                            <a href="{{ route('news.show', $recent) }}" class="text-decoration-none d-flex gap-3 align-items-center sidebar-item">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $recent->image) }}" class="rounded-3 shadow-sm sidebar-thumb" alt="{{ $recent->title }}">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark fw-bold small lh-sm line-clamp-2 sidebar-title">{{ $recent->title }}</h6>
                                    <small class="text-muted extra-small">{{ $recent->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow rounded-4 text-white overflow-hidden cta-gradient-card">
                    <div class="card-body p-4 text-center">
                        <div class="cta-icon-wrapper mb-3">
                            <i class="bi bi-cart-check-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold h6 mb-2">Turkmen Market Online</h5>
                        <p class="small opacity-75 mb-4">Iň täze harytlar we amatly bahalar diňe biziň dükanyňyzda.</p>
                        <a href="{{ route('shop') }}" class="btn btn-light w-100 rounded-pill fw-bold py-2 shadow-sm border-0 cta-btn">
                            Dükana gir <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #fcfcfc;
        color: #333;
    }

    /* Layout Helpers */
    .bg-primary-soft {
        background-color: rgba(40, 81, 121, 0.08);
    }

    .extra-small {
        font-size: 0.72rem;
    }

    .fw-extrabold {
        font-weight: 800;
    }

    .italic {
        font-style: italic;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Video Player Specific UI */
    .video-card-wrapper {
        max-width: 100%;
        margin: 0 auto;
    }

    .video-container {
        background: #000;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .video-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
        background: rgba(40, 81, 121, 0.9);
        backdrop-filter: blur(5px);
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .local-video-bg {
        background: radial-gradient(circle, #1e3f5f 0%, #000 100%);
    }

    .player-custom {
        max-height: 500px;
        outline: none;
    }

    /* Header & Hero */
    .article-hero-container {
        height: 240px;
    }

    .article-hero-img {
        object-fit: cover;
    }

    .editorial-title {
        letter-spacing: -1px;
        line-height: 1.15;
    }

    /* Content */
    .rich-text-body {
        font-size: 1.125rem;
        line-height: 1.85;
    }

    .summary-box {
        background: #f8f9fa;
        border-left: 5px solid #285179;
    }

    /* Buttons & Cards */
    .share-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .share-icon-btn.tg:hover {
        background: #0088cc;
        color: white;
        border-color: #0088cc;
    }

    .share-icon-btn.wa:hover {
        background: #25d366;
        color: white;
        border-color: #25d366;
    }

    .related-card {
        transition: all 0.3s ease;
        position: relative;
        top: 0;
    }

    .related-card:hover {
        top: -8px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .related-img-wrapper {
        height: 140px;
        overflow: hidden;
    }

    .related-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }

    .related-card:hover img {
        transform: scale(1.1);
    }

    .sidebar-sticky {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    .sidebar-thumb {
        width: 65px;
        height: 65px;
        object-fit: cover;
    }

    .sidebar-item:hover .sidebar-title {
        color: #285179 !important;
    }

    .cta-gradient-card {
        background: linear-gradient(145deg, #1e3f5f 0%, #285179 100%);
    }

    .cta-icon-wrapper {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .article-hero-container {
            height: 200px;
        }

        .display-6 {
            font-size: 1.75rem;
        }
    }
</style>

<script>
    window.addEventListener('scroll', function() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById("reading-progress").style.width = scrolled + "%";
    });
</script>
@endsection