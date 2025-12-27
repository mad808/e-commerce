@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="text-muted text-uppercase mb-3">Banner Details</h6>

                    <div class="mb-3">
                        <label class="form-label">Banner Image</label>
                        <input type="file" name="image" class="form-control">
                        @if($slider->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $slider->image) }}" class="rounded border" width="100%">
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banner Type</label>
                            <select name="type" class="form-select">
                                <option value="home_main" {{ $slider->type == 'home_main' ? 'selected' : '' }}>Home Page Main Slider</option>
                                <option value="product_detail_banner" {{ $slider->type == 'product_detail_banner' ? 'selected' : '' }}>Product Detail Sidebar</option>
                                <option value="popup_ad" {{ $slider->type == 'popup_ad' ? 'selected' : '' }}>Home Page Popup (15 sec)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $slider->sort_order }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $slider->title }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ $slider->subtitle }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Link / URL</label>
                        <input type="url" name="link" class="form-control" value="{{ $slider->link }}">
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $slider->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="activeSwitch">Active</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">Update Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection