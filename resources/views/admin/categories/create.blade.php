@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">Add New Category</h5>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Icon/Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between py-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-decoration-none text-muted">Back</a>
                        <button type="submit" class="btn btn-primary px-5">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection