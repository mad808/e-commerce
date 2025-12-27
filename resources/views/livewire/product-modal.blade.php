<div>
    @if($isOpen && $product)
    <div class="modal-backdrop show animate__animated animate__fadeIn"
        style="background-color: rgba(0,0,0,0.6); z-index: 1050; backdrop-filter: blur(4px);"
        wire:click="close"></div>

    <div class="modal d-block" tabindex="-1" style="z-index: 1055;">
        <div class="modal-dialog modal-dialog-centered modal-lg animate__animated animate__zoomIn animate__faster">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="modal-header border-0 pb-0 pt-3 px-4">
                    <h5 class="modal-title fw-bold text-secondary">Görnüşleri saýlaň</h5>
                    <button type="button" class="btn-close" wire:click="close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <div class="bg-white border rounded-4 d-flex align-items-center justify-content-center p-3 shadow-sm position-relative h-100" style="min-height: 300px;">
                                @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                @else
                                <i class="bi bi-image fs-1 text-muted opacity-25"></i>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-7 d-flex flex-column justify-content-center">
                            <div class="mb-3">
                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">
                                    {{ $product->category->name ?? 'Kategoriýa' }}
                                </small>
                                <h3 class="fw-bold text-dark mt-1">{{ $product->name }}</h3>
                            </div>

                            <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                                <div>
                                    <h2 class="fw-bold mb-0" style="color: #405FA5;">
                                        {{ number_format($product->price * $quantity, 2) }} m
                                    </h2>
                                </div>
                            </div>

                            @if($product->attributes)
                            @foreach($product->attributes as $attr)
                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-secondary text-uppercase">{{ $attr->name }}:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(explode(',', $attr->pivot->value) as $val)
                                    @php $trimmedVal = trim($val); @endphp
                                    <label class="modal-attr-label">
                                        <input type="radio"
                                            wire:model="selectedAttributes.{{ $attr->name }}"
                                            value="{{ $trimmedVal }}"
                                            class="d-none">
                                        <span class="modal-attr-box">{{ $trimmedVal }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            @endif

                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group" style="width: 120px;">
                                    <button class="btn btn-outline-secondary" wire:click="decrement">-</button>
                                    <input type="text" class="form-control text-center fw-bold bg-white" value="{{ $quantity }}" readonly>
                                    <button class="btn btn-outline-secondary" wire:click="increment">+</button>
                                </div>

                                <button wire:click="addToCart" wire:loading.attr="disabled" class="btn btn-royal flex-grow-1">
                                    <span wire:loading.remove wire:target="addToCart">SEBEDE GOŞ</span>
                                    <span wire:loading wire:target="addToCart" class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-royal {
            background-color: #405FA5;
            color: white;
            height: 45px;
            border-radius: 8px;
            font-weight: bold;
        }

        .modal-attr-box {
            padding: 6px 14px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
            display: inline-block;
        }

        /* This makes the selected radio button look active */
        .modal-attr-label input:checked+.modal-attr-box {
            background-color: #405FA5;
            color: white;
            border-color: #405FA5;
        }
    </style>
    @endif
</div>