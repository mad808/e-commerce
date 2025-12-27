<!-- Location Modal -->
<div class="modal fade" id="locationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-brand text-white rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-geo-alt-fill me-2"></i> Choose Your Region
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-4">Please select your delivery location to see correct prices and delivery options.</p>

                <div class="row g-3">
                    @php
                    $locations = \App\Models\Location::where('is_active', true)->get();
                    @endphp

                    @foreach($locations as $loc)
                    <div class="col-6">
                        <button onclick="selectLocation({{ $loc->id }})" class="btn btn-outline-primary w-100 py-3 fw-bold rounded-3 h-100 location-btn" style="border-color: var(--brand-blue); color: var(--brand-blue);">
                            {{ $loc->name }}
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectLocation(id) {
        fetch("{{ route('set.location') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    location_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    location.reload();
                }
            });
    }

    function openLocationModal() {
        var myModal = new bootstrap.Modal(document.getElementById('locationModal'));
        myModal.show();
    }
</script>

<style>
    .location-btn:hover {
        background-color: var(--brand-blue) !important;
        color: white !important;
    }
</style>

<!-- <button type="button" class="btn btn-primary" onclick="openLocationModal()">
    <i class="bi bi-geo-alt-fill me-2"></i> Change Location
</button> -->

<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal">
    <i class="bi bi-geo-alt-fill me-2"></i> Change Location
</button> -->