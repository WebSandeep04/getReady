@extends('layouts.app')

@section('title', 'Edit Cloth')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/listed-clothes.css') }}">
<style>
    /* Ensure proper spacing for fixed navigation on edit page */
    .container {
        margin-top: 70px;
    }

    .top-nav {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1030 !important;
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    

    .image-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin: 5px;
        border: 2px solid #ddd;
    }
    .image-container {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    .remove-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        cursor: pointer;
        font-size: 12px;
    }
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f9f9f9;
        cursor: pointer;
        transition: all 0.3s;
    }
    .upload-area:hover {
        border-color: #007bff;
        background: #f0f8ff;
    }
    .upload-area.dragover {
        border-color: #007bff;
        background: #e3f2fd;
    }
    .availability-block {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    .availability-block:hover {
        border-color: #007bff;
        background: #f0f8ff;
    }
    .availability-block .form-control-sm {
        font-size: 0.875rem;
    }
    .availability-block .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .alert-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-warning">Edit Cloth</h2>
                <a href="{{ route('listed.clothes') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Listed Clothes
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <!-- <h5 class="mb-0">Edit Cloth Details</h5> -->
                </div>
                <div class="card-body">
                    <form id="editClothForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $cloth->title }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $cloth->category == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender *</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ $cloth->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $cloth->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Unisex" {{ $cloth->gender == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="{{ $cloth->brand }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fabric">Fabric Type</label>
                                    <select class="form-control" id="fabric" name="fabric">
                                        <option value="">Select Fabric Type</option>
                                        @foreach($fabricTypes as $fabricType)
                                            <option value="{{ $fabricType->id }}" {{ $cloth->fabric == $fabricType->id ? 'selected' : '' }}>
                                                {{ $fabricType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <select class="form-control" id="color" name="color">
                                        <option value="">Select Color</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ $cloth->color == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="size">Size *</label>
                                    <select class="form-control" id="size" name="size" required>
                                        <option value="">Select Size</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ $cloth->size == $size->id ? 'selected' : '' }}>
                                                {{ $size->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bottom_type">Bottom Type</label>
                                    <select class="form-control" id="bottom_type" name="bottom_type">
                                        <option value="">Select Bottom Type</option>
                                        @foreach($bottomTypes as $bottomType)
                                            <option value="{{ $bottomType->id }}" {{ $cloth->bottom_type == $bottomType->id ? 'selected' : '' }}>
                                                {{ $bottomType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fit_type">Fit Type</label>
                                    <select class="form-control" id="fit_type" name="fit_type">
                                        <option value="">Select Fit Type</option>
                                        @foreach($fitTypes as $fitType)
                                            <option value="{{ $fitType->id }}" {{ $cloth->fit_type == $fitType->id ? 'selected' : '' }}>
                                                {{ $fitType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="condition">Condition *</label>
                                    <select class="form-control" id="condition" name="condition" required>
                                        <option value="">Select Condition</option>
                                        <option value="Brand New" {{ $cloth->condition == 'Brand New' ? 'selected' : '' }}>Brand New</option>
                                        <option value="Like New" {{ $cloth->condition == 'Like New' ? 'selected' : '' }}>Like New</option>
                                        <option value="Good Condition" {{ $cloth->condition == 'Good Condition' ? 'selected' : '' }}>Good Condition</option>
                                        <option value="Worn but Usable" {{ $cloth->condition == 'Worn but Usable' ? 'selected' : '' }}>Worn but Usable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rent_price">Rent Price (₹) *</label>
                                    <input type="number" class="form-control" id="rent_price" name="rent_price" value="{{ $cloth->rent_price }}" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="security_deposit">Security Deposit (₹) *</label>
                                    <input type="number" class="form-control" id="security_deposit" name="security_deposit" value="{{ $cloth->security_deposit }}" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="chest_bust">Chest/Bust (inches)</label>
                                    <input type="text" class="form-control" id="chest_bust" name="chest_bust" value="{{ $cloth->chest_bust }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="waist">Waist (inches)</label>
                                    <input type="text" class="form-control" id="waist" name="waist" value="{{ $cloth->waist }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="length">Length (inches)</label>
                                    <input type="text" class="form-control" id="length" name="length" value="{{ $cloth->length }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shoulder">Shoulder (inches)</label>
                                    <input type="text" class="form-control" id="shoulder" name="shoulder" value="{{ $cloth->shoulder }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sleeve_length">Sleeve Length (inches)</label>
                                    <input type="text" class="form-control" id="sleeve_length" name="sleeve_length" value="{{ $cloth->sleeve_length }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="defects">Defects (if any)</label>
                            <textarea class="form-control" id="defects" name="defects" rows="3">{{ $cloth->defects }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" {{ $cloth->is_cleaned ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_cleaned">Is Cleaned</label>
                            </div>
                        </div>

                        <!-- Availability Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">📆 Availability Management</h5>
                                <small class="text-muted">Manage when your cloth is available for rent or blocked for personal use</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Available Dates</h6>
                                        <p class="text-muted small">Set specific dates when this cloth is available for rent</p>
                                        <div class="alert alert-info alert-sm">
                                            <i class="fas fa-info-circle"></i>
                                            <small>Tip: Leave empty if the cloth is always available. Add specific dates for limited availability.</small>
                                        </div>
                                        <div id="available-dates">
                                            @foreach($cloth->availabilityBlocks->where('type', 'available') as $index => $block)
                                                <div class="availability-block mb-3" data-type="available">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label class="small">Start Date</label>
                                                            <input type="date" class="form-control form-control-sm" name="availability_blocks[{{ $index }}][start_date]" value="{{ $block->start_date->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="small">End Date</label>
                                                            <input type="date" class="form-control form-control-sm" name="availability_blocks[{{ $index }}][end_date]" value="{{ $block->end_date->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="small">&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block" onclick="removeAvailabilityBlock(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="availability_blocks[{{ $index }}][type]" value="available">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm" onclick="addAvailabilityBlock('available')">
                                            <i class="fas fa-plus"></i> Add Available Date
                                        </button>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6>Blocked Dates</h6>
                                        <p class="text-muted small">Set dates when you plan to use the cloth yourself</p>
                                        <div class="alert alert-warning alert-sm">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <small>Tip: Block dates when you'll be using the cloth personally to avoid rental conflicts.</small>
                                        </div>
                                        <div id="blocked-dates">
                                            @foreach($cloth->availabilityBlocks->where('type', 'blocked') as $index => $block)
                                                <div class="availability-block mb-3" data-type="blocked">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label class="small">Start Date</label>
                                                            <input type="date" class="form-control form-control-sm" name="availability_blocks[{{ $index + 100 }}][start_date]" value="{{ $block->start_date->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="small">End Date</label>
                                                            <input type="date" class="form-control form-control-sm" name="availability_blocks[{{ $index + 100 }}][end_date]" value="{{ $block->end_date->format('Y-m-d') }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="small">&nbsp;</label>
                                                            <button type="button" class="btn btn-danger btn-sm btn-block" onclick="removeAvailabilityBlock(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <label class="small">Reason (optional)</label>
                                                            <input type="text" class="form-control form-control-sm" name="availability_blocks[{{ $index + 100 }}][reason]" value="{{ $block->reason }}" placeholder="e.g., Personal use, Maintenance">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="availability_blocks[{{ $index + 100 }}][type]" value="blocked">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="addAvailabilityBlock('blocked')">
                                            <i class="fas fa-plus"></i> Add Blocked Date
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Cloth
                            </button>
                            <a href="{{ route('listed.clothes') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Images Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Manage Images</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="current-images">
                                @foreach($cloth->images as $image)
                                    <div class="image-container" data-image-id="{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Cloth Image" class="image-preview">
                                        <span class="remove-image" onclick="removeImage({{ $image->id }})">×</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="upload-area" id="upload-area" onclick="document.getElementById('image-upload').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="mb-0">Click to upload new images</p>
                                <small class="text-muted">Drag and drop images here</small>
                            </div>
                            <input type="file" id="image-upload" multiple accept="image/*" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Variables -->
<script>
    // Pass PHP variables to JavaScript
    window.editClothUpdateUrl = '{{ route("listed.clothes.update", $cloth->id) }}';
    window.listedClothesUrl = '{{ route("listed.clothes") }}';
    window.availableCounter = {{ $cloth->availabilityBlocks->where('type', 'available')->count() }};
    window.blockedCounter = {{ $cloth->availabilityBlocks->where('type', 'blocked')->count() }};
</script>

<!-- Include external JavaScript file -->
<script src="{{ asset('js/edit-cloth.js') }}"></script>

@endsection 