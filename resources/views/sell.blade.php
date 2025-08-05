@extends('layouts.app-simple')

@section('title', 'Get Ready - Sell Cloth')

@section('styles')
<link rel="stylesheet" href="css/sell.css">
@endsection

@section('content')
<div class="sell-logo">
  <img src="images/logo.png" alt="Logo">
</div>

<div class="container">
  <div class="steps">
    <span class="step active">Basic Garment Information</span>
    <span class="step">Garment Specifications</span>
    <span class="step">Condition & Availability</span>
  </div>

  <form id="form" method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="step-content active">
      <input type="text" name="title" placeholder="Title" value="{{ old('title') }}" required>
      @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <select name="category" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
      @error('category')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
        <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
      </select>
      @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <input type="text" name="brand" placeholder="Brand (Optional)" value="{{ old('brand') }}">
      @error('brand')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="step-content">
      <select name="fabric" required>
        <option value="">Select Fabric Type</option>
        @foreach($fabric_types as $fabric)
          <option value="{{ $fabric->id }}" {{ old('fabric') == $fabric->id ? 'selected' : '' }}>
            {{ $fabric->name }}
          </option>
        @endforeach
      </select>
      @error('fabric')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <select name="color" required>
        <option value="">Select Color</option>
        @foreach($colors as $color)
          <option value="{{ $color->id }}" {{ old('color') == $color->id ? 'selected' : '' }}>
            {{ $color->name }}
          </option>
        @endforeach
      </select>
      @error('color')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <select name="bottom_type" required>
        <option value="">Select Bottom Type</option>
        @foreach($bottom_types as $bottom_type)
          <option value="{{ $bottom_type->id }}" {{ old('bottom_type') == $bottom_type->id ? 'selected' : '' }}>
            {{ $bottom_type->name }}
          </option>
        @endforeach
      </select>
      @error('bottom_type')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <select name="size" required>
        <option value="">Select Size</option>
        @foreach($sizes as $size)
          <option value="{{ $size->id }}" {{ old('size') == $size->id ? 'selected' : '' }}>
            {{ $size->name }}
          </option>
        @endforeach
      </select>
      @error('size')<div class="text-danger small">{{ $message }}</div>@enderror

      <div class="measurements mt-3">
        <label><strong>Exact Measurements (for better fit understanding) (optional)</strong></label>
        <input type="text" name="chest_bust" placeholder="Chest/Bust (inches)" value="{{ old('chest_bust') }}">
        <input type="text" name="waist" placeholder="Waist (inches)" value="{{ old('waist') }}">
        <input type="text" name="length" placeholder="Length (inches)" value="{{ old('length') }}">
        <input type="text" name="shoulder" placeholder="Shoulder (inches)" value="{{ old('shoulder') }}">
        <input type="text" name="sleeve_length" placeholder="Sleeve Length (inches)" value="{{ old('sleeve_length') }}">
      </div>
      
      <select name="body_type_fit" required>
        <option value="">Select Body Fit Type</option>
        @foreach($body_type_fits as $body_type_fit)
          <option value="{{ $body_type_fit->id }}" {{ old('body_type_fit') == $body_type_fit->id ? 'selected' : '' }}>
            {{ $body_type_fit->name }}
          </option>
        @endforeach
      </select>
      @error('body_type_fit')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="step-content">
      <select name="condition" required>
        <option value="">Select Garment Condition</option>
        <option value="Brand New" {{ old('condition') == 'Brand New' ? 'selected' : '' }}>Brand New</option>
        <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
        <option value="Good Condition" {{ old('condition') == 'Good Condition' ? 'selected' : '' }}>Good Condition</option>
        <option value="Worn but Usable" {{ old('condition') == 'Worn but Usable' ? 'selected' : '' }}>Worn but Usable</option>
      </select>
      @error('condition')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <textarea name="defects" placeholder="Any Defects (Optional)">{{ old('defects') }}</textarea>
      @error('defects')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <input type="number" name="rent_price" placeholder="Rent Price (₹)" value="{{ old('rent_price') }}" required>
      @error('rent_price')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <input type="number" name="security_deposit" placeholder="Security Deposit (₹)" value="{{ old('security_deposit') }}" required>
      @error('security_deposit')<div class="text-danger small">{{ $message }}</div>@enderror
      
      <div class="mb-2">Upload up to 4 images (at least 1 required):</div>
      <input type="file" name="images[]" accept="image/*" required>
      <input type="file" name="images[]" accept="image/*">
      <input type="file" name="images[]" accept="image/*">
      <input type="file" name="images[]" accept="image/*">
      <small class="text-muted">You can upload up to 4 images. At least 1 is required.</small>
      @error('images.*')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <button type="button" id="prevBtn" class="next-btn" style="display: none;">←</button> 
    <button type="button" id="nextBtn" class="next-btn">→</button>
    <button type="submit" id="submitBtn" class="submit-btn" style="display: none;">Submit</button>
  </form>
</div>

<div class="decorative">
  <img src="images/footer.png" alt="Decoration">
</div>
@endsection

@section('scripts')
<script src="js/sell.js"></script>
@endsection
