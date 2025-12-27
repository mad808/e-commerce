@extends('layouts.app')

@section('title', 'Habarlar we Makalalar')

@section('content')
<section class="news-hero py-5 mb-5 text-white" style="background: linear-gradient(135deg, #1e3f5f 0%, #285179 100%);">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none small">Baş sahypa</a></li>
                        <li class="breadcrumb-item active text-white small" aria-current="page">Habarlar</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-extrabold mb-3">Habarlar we Makalalar</h1>
                <p class="lead opacity-75">Bazaryň iň täze habarlary, hünärmen makalalary we trendleri bilen tanşyň.</p>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-end">
                <i class="bi bi-newspaper display-1 opacity-25"></i>
            </div>
        </div>
    </div>
</section>

<div class="container pb-5">
    @if($news->count() > 0)
    <div class="row g-4">
        @foreach($news as $item)
        <div class="col-12">
            <article class="news-card card border-0 bg-white rounded-4 overflow-hidden shadow-sm hover-lift">
                <div class="row g-0">
                    <div class="col-md-4 col-lg-3">
                        <div class="position-relative h-100 overflow-hidden">
                            <a href="{{ route('news.show', $item) }}" class="d-block h-100">
                                @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    class="img-fluid h-100 w-100 news-img-zoom"
                                    alt="{{ $item->title }}">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center h-100 min-h-200">
                                    <i class="bi bi-image fs-1 text-muted opacity-25"></i>
                                </div>
                                @endif
                            </a>
                            <div class="category-badge position-absolute top-0 start-0 m-3">
                                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm small">Täze</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-9">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column h-100">
                            <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                                <span><i class="bi bi-calendar3 me-1"></i> {{ $item->created_at->format('d.m.Y') }}</span>
                                <span class="d-none d-sm-inline">•</span>
                                <span><i class="bi bi-eye me-1"></i> {{ number_format($item->views) }} görüldi</span>
                            </div>

                            <h2 class="h3 fw-bold mb-3 editorial-title">
                                <a href="{{ route('news.show', $item) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $item->title }}
                                </a>
                            </h2>

                            <p class="text-secondary mb-4 flex-grow-1 line-clamp-3">
                                {{ $item->summary ?? Str::limit(strip_tags($item->body), 200) }}
                            </p>

                            <div class="mt-auto d-flex align-items-center justify-content-between pt-4 border-top">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-small">
                                        <i class="bi bi-person-circle fs-5 text-muted opacity-50"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">Admin Tarapyndan</span>
                                </div>
                                <a href="{{ route('news.show', $item) }}" class="btn-read-more">
                                    Doly oka <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        @endforeach
    </div>

    @if($news->hasPages())
    <div class="mt-5 pt-4 d-flex justify-content-center">
        {{ $news->links('pagination::bootstrap-5') }}
    </div>
    @endif

    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
        </div>
        <h4 class="fw-bold text-dark">Häzirlikçe habar ýok</h4>
        <p class="text-muted mb-4">Uzak garaşdyrmarys, täze habarlarymyza garaşyň.</p>
        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold">
            <i class="bi bi-house me-2"></i> Baş sahypa gaýt
        </a>
    </div>
    @endif
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f4f7f9;
    }

    .fw-extrabold {
        font-weight: 800;
    }

    /* Header Styling */
    .news-hero {
        margin-top: -3rem;
        padding-top: 6rem !important;
        margin-bottom: 4rem;
    }

    /* Card Animations & Styling */
    .news-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0, 0, 0, 0.03) !important;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(40, 81, 121, 0.12) !important;
    }

    .news-img-zoom {
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .news-card:hover .news-img-zoom {
        transform: scale(1.08);
    }

    /* Typography */
    .editorial-title {
        line-height: 1.3;
        letter-spacing: -0.02em;
    }

    .hover-primary:hover {
        color: #285179 !important;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Buttons & Links */
    .btn-read-more {
        color: #285179;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        padding: 0.5rem 0;
        border-bottom: 2px solid transparent;
    }

    .btn-read-more:hover {
        border-bottom-color: #285179;
        padding-right: 5px;
    }

    .min-h-200 {
        min-height: 200px;
    }

    /* Pagination Override (assuming Bootstrap 5) */
    .pagination .page-link {
        border: none;
        margin: 0 5px;
        border-radius: 8px;
        color: #285179;
        font-weight: 600;
    }

    .pagination .page-item.active .page-link {
        background-color: #285179;
        color: white;
    }

    @media (max-width: 768px) {
        .news-hero {
            padding-top: 4rem !important;
        }

        .min-h-200 {
            min-height: 250px;
        }
    }
</style>
@endsection