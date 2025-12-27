<div>
    <div class="d-flex gap-2">
        <!-- Quantity -->
        <div class="input-group" style="width: 130px;">
            <button type="button" class="btn btn-outline-secondary" wire:click="decrement">-</button>
            <input type="text" class="form-control text-center border-secondary fw-bold" value="{{ $quantity }}" readonly>
            <button type="button" class="btn btn-outline-secondary" wire:click="increment">+</button>
        </div>

        <!-- Add Button -->
        <!-- Added wire:target="addToCart" to the button attributes -->
        <button wire:click="addToCart" 
                wire:loading.attr="disabled"
                wire:target="addToCart"
                class="btn btn-success flex-grow-1 fw-bold shadow-sm d-flex align-items-center justify-content-center"
                style="height: 45px; background-color: #198754; border-color: #198754;">
            
            <div class="d-flex align-items-center gap-2">
                
                <!-- ICON: Hide only when adding to cart -->
                <i class="bi bi-bag-plus-fill fs-5" wire:loading.remove wire:target="addToCart"></i>
                
                <!-- SPINNER: Show only when adding to cart -->
                <div wire:loading wire:target="addToCart" class="spinner-border text-white" role="status" 
                     style="width: 1.2rem; height: 1.2rem; border-width: 2px;"></div>

                <span>Sebede goş</span>
            </div>

        </button>
    </div>

    @if (session()->has('success'))
    <div class="text-success small mt-2 fw-bold d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    </div>
    @endif
</div>