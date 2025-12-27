@extends('admin.layouts.app')

@section('title', 'Add Banner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h6 class="text-muted text-uppercase mb-3">Banner Details</h6>

                    <div class="mb-3">
                        <label class="form-label">Banner Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" required>
                        <div class="form-text">Allowed formats: JPG, PNG, WEBP, GIF. Max 10MB.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banner Type</label>
                            <select name="type" class="form-select">
                                <option value="home_main">Home Page Main Slider</option>
                                <option value="product_detail_banner">Product Detail Sidebar</option>
                                <option value="popup_ad">Home Page Popup (15 sec)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title (Optional)</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Big Sale 50% Off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle (Optional)</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="e.g. On all electronics this week">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Link / URL (Optional)</label>
                        <input type="url" name="link" class="form-control" placeholder="https://...">
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked>
                        <label class="form-check-label" for="activeSwitch">Active</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Save Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection