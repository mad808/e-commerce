@extends('admin.layouts.app')

@section('title', 'Edit Attribute')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Attribute</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.attributes.update', $attribute->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Attribute Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $attribute->name }}" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Attribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection