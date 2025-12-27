<div>
    <button wire:click.prevent="toggle" 
            class="btn btn-light btn-sm rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center" 
            style="width: 32px; height: 32px; transition: 0.2s;"
            title="Add to Wishlist">
        
        @if($isFavorited)
            <i class="bi bi-heart-fill text-danger header-icon-animate"></i>
        @else
            <i class="bi bi-heart" style="color: #198754;"></i>
        @endif

    </button>

    <style>
        .header-icon-animate {
            animation: pop 0.3s ease-out;
        }
        @keyframes pop {
            0% { transform: scale(0.8); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>
</div>