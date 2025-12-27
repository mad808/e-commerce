@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Edit Category: <span class="text-primary">{{ $category->name }}</span></h5>
                </div>
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Change Image</label>
                            <input type="file" name="image" class="form-control mb-2">
                            @if($category->image)
                            <div class="p-2 border rounded bg-light d-inline-block">
                                <img src="{{ asset('storage/' . $category->image) }}" height="80">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between py-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-muted text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-success px-5">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection