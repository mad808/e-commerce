@extends('admin.layouts.app')

@section('title', 'Harydy redaktirlemek')

@section('content')
<div class="container-fluid pb-5">
    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="bi bi-pencil-square text-primary fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Haryt maglumatlaryny düzediň</h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harydyň ady</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Düşündiriş</label>
                            <textarea name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategoriýa</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Barkod</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-info bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="bi bi-list-stars text-info fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Haryt aýratynlyklary</h5>
                        </div>

                        <div class="mb-3">
                            <select id="attributeSelect" class="form-select shadow-none" onchange="handleAttributeChange()">
                                <option value="">Täze aýratynlyk goşuň...</option>
                                @foreach($attributes as $attr)
                                <option value="{{ $attr->id }}" data-name="{{ $attr->name }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="attributeInputs" class="row g-3">
                            @foreach($product->attributes as $pAttr)
                            <div class="col-md-6 attribute-item" id="attr-row-{{ $pAttr->id }}">
                                <div class="attr-badge shadow-sm border-primary-subtle">
                                    <label class="small fw-bold text-primary mb-1 d-block">{{ $pAttr->name }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="attributes[{{ $pAttr->id }}]" value="{{ old('attributes.'.$pAttr->id, $pAttr->pivot->value) }}" class="form-control border-0 bg-transparent shadow-none">
                                        <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="removeAttributeRow('{{ $pAttr->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="activeSwitch">Aktiw görkezilsin</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm" style="border-radius: 10px;">
                                <i class="bi bi-check2-all me-2"></i> Ýatda sakla
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light py-2">Yza gaýt</a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Bahalandyrma</h6>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Özi düşýän bahasy (Cost)</label>
                            <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price ?? '') }}" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-7 mb-3">
                                <label class="form-label small text-muted">Esasy baha (Base Price)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="price" id="base_price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
                                    <span class="input-group-text bg-light">TMT</span>
                                </div>
                            </div>

                            <div class="col-md-5 mb-3">
                                <label class="form-label small text-primary fw-bold">Arzanladyş %</label>
                                <div class="input-group">
                                    <input type="number" name="discount_percent" id="discount_percent" class="form-control border-primary-subtle" value="{{ old('discount_percent', $product->discount_percent ?? 0) }}" min="0" max="99">
                                    <span class="input-group-text bg-primary text-white border-0">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Müşderi üçin baha:</span>
                                <h4 class="mb-0 fw-bold text-success" id="final_price_preview">0.00 TMT</h4>
                            </div>
                            <div id="profit_margin" class="small mt-2 fw-bold text-center p-1 rounded d-none"></div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small text-muted">Stock (Sany)</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}" required>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Esasy surat</h6>
                        <div class="image-preview-wrapper mb-3">
                            <label for="mainImageInput" class="d-block cursor-pointer">
                                <div id="mainPreview" class="border border-2 border-dashed rounded-3 d-flex align-items-center justify-content-center bg-light overflow-hidden" style="height: 200px;">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                    @else
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                    @endif
                                </div>
                            </label>
                            <input type="file" name="image" id="mainImageInput" class="d-none" onchange="previewMainImage(this)">
                        </div>

                        <h6 class="fw-bold mb-2">Galereýa</h6>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($product->images as $img)
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $img->image) }}" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        <input type="file" name="images[]" class="form-control form-control-sm" multiple>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .attr-badge {
        background: #fdfdfd;
        border: 1px solid #eee;
        padding: 10px;
        border-radius: 12px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
    }
</style>

<script>
    function previewMainImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainPreview').innerHTML = `<img src="${e.target.result}" class="img-fluid h-100 w-100" style="object-fit: cover;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function handleAttributeChange() {
        const select = document.getElementById('attributeSelect');
        const option = select.options[select.selectedIndex];
        if (!option.value) return;

        if (document.getElementById(`attr-row-${option.value}`)) {
            alert('Bu aýratynlyk eýýäm goşuldy!');
            select.value = "";
            return;
        }

        const html = `
            <div class="col-md-6" id="attr-row-${option.value}">
                <div class="attr-badge shadow-sm border-primary-subtle">
                    <label class="small fw-bold text-primary mb-1 d-block">${option.text}</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="attributes[${option.value}]" class="form-control border-0 bg-transparent shadow-none" placeholder="Bahasyny ýazyň...">
                        <button type="button" class="btn btn-link text-danger p-0 ms-2" onclick="removeAttributeRow('${option.value}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;
        document.getElementById('attributeInputs').insertAdjacentHTML('beforeend', html);
        select.value = "";
    }

    function removeAttributeRow(id) {
        const row = document.getElementById(`attr-row-${id}`);
        if (row) row.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const basePriceInp = document.getElementById('base_price');
        const discountInp = document.getElementById('discount_percent');
        const costInp = document.getElementById('cost_price');
        const preview = document.getElementById('final_price_preview');
        const marginDiv = document.getElementById('profit_margin');

        function calculate() {
            const base = parseFloat(basePriceInp.value) || 0;
            const discount = parseFloat(discountInp.value) || 0;
            const cost = parseFloat(costInp.value) || 0;

            // Calculate Final Price
            const final = base - (base * (discount / 100));
            preview.innerText = final.toFixed(2) + ' TMT';

            // Calculate Margin (Safety Check)
            if (cost > 0 && final > 0) {
                const profit = final - cost;
                const marginPercent = (profit / final) * 100;

                marginDiv.classList.remove('d-none', 'bg-danger-subtle', 'text-danger', 'bg-success-subtle', 'text-success');

                if (profit <= 0) {
                    marginDiv.innerText = `Zyýan: ${profit.toFixed(2)} TMT`;
                    marginDiv.classList.add('bg-danger-subtle', 'text-danger');
                } else {
                    marginDiv.innerText = `Peýda: ${profit.toFixed(2)} TMT (${marginPercent.toFixed(1)}%)`;
                    marginDiv.classList.add('bg-success-subtle', 'text-success');
                }
                marginDiv.classList.remove('d-none');
            } else {
                marginDiv.classList.add('d-none');
            }
        }

        basePriceInp.addEventListener('input', calculate);
        discountInp.addEventListener('input', calculate);
        costInp.addEventListener('input', calculate);
        calculate();
    });
</script>
@endsection