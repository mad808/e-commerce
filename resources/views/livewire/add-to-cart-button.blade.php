<div class="w-100">
    
    @if($withQuantity)
        <!-- ============================================= -->
        <!-- LAYOUT 1: DETAIL PAGE (With Quantity + - )    -->
        <!-- ============================================= -->
        <div class="d-flex align-items-center gap-3">
            
            <!-- Quantity Selector -->
            <div class="input-group" style="width: 140px; border: 2px solid #285179; border-radius: 50px; overflow: hidden; height: 50px;">
                <button type="button" class="btn btn-link text-decoration-none fw-bold hover-bg" 
                        wire:click="decrement" 
                        style="color: #285179; width: 40px; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; border: none;">
                    -
                </button>
                
                <input type="text" class="form-control text-center border-0 bg-transparent fw-bold text-dark h-100" 
                       value="{{ $quantity }}" readonly 
                       style="font-size: 1.1rem; padding: 0;">
                
                <button type="button" class="btn btn-link text-decoration-none fw-bold hover-bg" 
                        wire:click="increment" 
                        style="color: #285179; width: 40px; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; border: none;">
                    +
                </button>
            </div>

            <!-- Add Button -->
            <button wire:click="addToCart" 
                    wire:loading.attr="disabled"
                    wire:target="addToCart"
                    class="btn flex-grow-1 fw-bold shadow-sm d-flex align-items-center justify-content-center rounded-pill btn-custom-blue {{ $customClass }}"
                    style="height: 50px; font-size: 1.1rem;">
                
                <span wire:loading.remove wire:target="addToCart" class="d-flex align-items-center gap-2 text-light">
                    <i class="bi bi-bag-plus-fill fs-5"></i>
                    <span>Sebede goş</span>
                </span>
                
                <div wire:loading wire:target="addToCart" class="spinner-border text-white" role="status" 
                     style="width: 1.5rem; height: 1.5rem; border-width: 3px;"></div>
            </button>
        </div>

        @if (session()->has('success'))
        <div class="text-success small mt-3 fw-bold d-flex align-items-center animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        </div>
        @endif

    @else
        <!-- ============================================= -->
        <!-- LAYOUT 2: GRID VIEW (Your Original Button)    -->
        <!-- ============================================= -->
        <button wire:click.prevent="addToCart"
            wire:loading.attr="disabled"
            class="btn w-100 rounded-pill shadow-sm text-white position-relative btn-custom-blue {{ $customClass }}"
            title="Sebede goş"
            style="height: 40px; display: flex; align-items: center; justify-content: center;">

            <!-- 1. The Icon -->
            <i class="bi bi-cart-plus-fill fs-5" wire:loading.remove></i>

            <!-- 2. The Spinner -->
            <div wire:loading class="spinner-border text-white" role="status" 
                 style="width: 1.25rem; height: 1.25rem; border-width: 2px;"></div>
        </button>
    @endif

    <!-- Shared Styles -->
    <style>
        .btn-custom-blue {
            background-color: #35699eff; /* Keeping your specific blue */
            border: none;
            padding: 0;
            transition: all 0.3s ease;
        }
        .btn-custom-blue:hover {
            background-color: #4e96e2ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(53, 105, 158, 0.4) !important;
        }
        .btn-custom-blue:disabled {
            background-color: #4e96e2ff;
            opacity: 0.8;
            transform: none;
            cursor: not-allowed;
        }
        .hover-bg:hover {
            background-color: rgba(53, 105, 158, 0.1);
        }
    </style>
</div>