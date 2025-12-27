@php
    $loaderText = \App\Models\Setting::where('key', 'loader_text')->first()->value ?? 'Market ýüklenýär...';
    $loaderColor = \App\Models\Setting::where('key', 'loader_bg_color')->first()->value ?? '#ffffff';
    $loaderLogo = \App\Models\Setting::where('key', 'loader_logo')->first()->value ?? null;
@endphp

<style>
    #main-site-loader {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: {{ $loaderColor }};
        z-index: 10000;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease-in-out, visibility 0.5s;
    }
    .loader-img {
        width: 220px;
        margin-bottom: 20px;
        animation: loaderPulse 2s infinite ease-in-out;
    }
    .loader-text {
        font-family: sans-serif;
        font-weight: 600;
        color: #333;
    }
    @keyframes loaderPulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
    }
    .loader-fade { opacity: 0; visibility: hidden; }
</style>

<div id="main-site-loader">
    @if($loaderLogo)
        <img src="{{ asset('storage/' . $loaderLogo) }}" class="loader-img">
    @else
        <div class="spinner-border text-primary mb-3" role="status"></div>
    @endif
    <div class="loader-text">{{ $loaderText }}</div>
</div>

<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('main-site-loader');
        setTimeout(() => {
            loader.classList.add('loader-fade');
        }, 300); // Small delay for smoothness
    });
</script>