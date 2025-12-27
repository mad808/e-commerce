@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-2 p-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold m-0"><i class="bi bi-geo-alt me-2"></i>Locations</h2>
        <button type="button" class="btn btn-primary rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#createLocationModal">
            <i class="bi bi-plus-lg me-1"></i> Add New Location
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Location Name</th>
                        <th>Delivery Price</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $location->name }}</td>
                        <td>{{ number_format($location->delivery_price, 2) }} m.</td>

                        <td id="status-container-{{ $location->id }}">
                            <button type="button"
                                onclick="toggleLocationStatus({{ $location->id }})"
                                class="btn btn-sm border-0 p-0 shadow-none">
                                @if($location->is_active)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 status-badge">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active
                                </span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 status-badge">
                                    <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                </span>
                                @endif
                            </button>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light border edit-location-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editLocationModal"
                                    data-id="{{ $location->id }}"
                                    data-name="{{ $location->name }}"
                                    data-price="{{ $location->delivery_price }}"
                                    data-active="{{ $location->is_active }}">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>

                                <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this location?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border ms-1">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No locations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODALS - Added 'modal-dialog-centered' for better mobile viewing --}}

{{-- CREATE MODAL --}}
<div class="modal fade" id="createLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Location Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Ashgabat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Delivery Price (m.)</label>
                        <input type="number" step="0.01" name="delivery_price" class="form-control rounded-3" placeholder="0.00" required>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeCheck" checked>
                        <label class="form-check-label" for="activeCheck">Active for delivery</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Location</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form id="editLocationForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Location Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Delivery Price (m.)</label>
                        <input type="number" step="0.01" name="delivery_price" id="edit_price" class="form-control rounded-3" required>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_active">
                        <label class="form-check-label" for="edit_active">Active for delivery</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Location</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling for smoother badge interaction */
    .status-badge {
        display: inline-flex;
        align-items: center;
        transition: transform 0.1s ease;
    }
    .status-badge:active {
        transform: scale(0.95);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-location-btn');
        const editForm = document.getElementById('editLocationForm');
        const editName = document.getElementById('edit_name');
        const editPrice = document.getElementById('edit_price');
        const editActive = document.getElementById('edit_active');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');
                const active = this.getAttribute('data-active');
                
                editForm.action = `/admin/locations/${id}`;
                editName.value = name;
                editPrice.value = price;
                editActive.checked = (active == "1" || active == "true");
            });
        });
    });

    function toggleLocationStatus(id) {
        const container = document.getElementById(`status-container-${id}`);
        const originalContent = container.innerHTML;

        container.innerHTML = '<div class="spinner-border spinner-border-sm text-primary mx-3" role="status"></div>';

        fetch(`/admin/locations/${id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    container.innerHTML = `
                    <button type="button" onclick="toggleLocationStatus(${id})" class="btn btn-sm border-0 p-0 shadow-none">
                        <span class="badge ${data.is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'} rounded-pill px-3 py-2 status-badge">
                            <i class="bi ${data.is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} me-1"></i> 
                            ${data.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </button>
                `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = originalContent;
                alert('Error updating status. Please try again.');
            });
    }
</script>
@endsection