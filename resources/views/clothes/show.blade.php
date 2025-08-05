@extends('layouts.app-simple')

@section('title', $cloth->title)

@section('styles')
<link rel="stylesheet" href="../css/product.css" />
@endsection

@section('content')
<!-- Success/Error Messages -->
<div id="alert-container"></div>

<!-- Product Section -->
<section class="product-detail">
  <div class="product-image">
    @if($cloth->images->count())
      <img src="{{ asset('storage/' . $cloth->images->first()->image_path) }}" alt="{{ $cloth->title }}" />
      <div class="thumbnails">
        @foreach($cloth->images as $image)
          <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumb" />
        @endforeach
      </div>
    @else
      <img src="{{ asset('assets/images/lehenga.jpg') }}" alt="{{ $cloth->title }}" />
    @endif
    <button class="rent-button add-to-cart-btn" data-cloth-id="{{ $cloth->id }}" style="cursor: pointer;" disabled>
      <i class="bi bi-cart-plus me-2"></i>SELECT DATES TO RENT
    </button>
  </div>
  <div class="product-info">
    <h2>{{ $cloth->title }}</h2>
    <p><strong>Brand:</strong> {{ $cloth->brand ?? 'Not specified' }}</p>
    <p><strong>Category:</strong> {{ $cloth->category }}</p>
    <p><strong>Fabric:</strong> {{ $cloth->fabric ?? 'Not specified' }}</p>
    <p><strong>Color:</strong> {{ $cloth->color ?? 'Not specified' }}</p>
    <p><strong>Size:</strong> {{ $cloth->size }}</p>
    <p><strong>Bottom Type:</strong> {{ $cloth->bottom_type ?? 'Not specified' }}</p>
    <p><strong>Fit Type:</strong> {{ $cloth->fit_type ?? 'Not specified' }}</p>
    <p><strong>Condition:</strong> {{ $cloth->condition }}</p>
    <p><strong>Defects:</strong> {{ $cloth->defects ?? 'None' }}</p>
    <p><strong>Chest/Bust:</strong> {{ $cloth->chest_bust ?? 'Not specified' }}</p>
    <p><strong>Waist:</strong> {{ $cloth->waist ?? 'Not specified' }}</p>
    <p><strong>Length:</strong> {{ $cloth->length ?? 'Not specified' }}</p>
    <p><strong>Shoulder:</strong> {{ $cloth->shoulder ?? 'Not specified' }}</p>
    <p><strong>Sleeve Length:</strong> {{ $cloth->sleeve_length ?? 'Not specified' }}</p>
    <p><strong>Rent Price:</strong> ₹{{ number_format($cloth->rent_price) }} <small class="text-muted">(per day)</small></p>
    <p><strong>Security Deposit:</strong> ₹{{ number_format($cloth->security_deposit) }}</p>
    
    <!-- Availability Information -->
    <div class="availability-info mt-3">
      <h5>📅 Availability</h5>
      @if($cloth->availabilityBlocks->where('type', 'available')->count() > 0)
        <div class="alert alert-info">
          <strong>Available Dates:</strong>
          <ul class="mb-0 mt-2">
            @foreach($cloth->availabilityBlocks->where('type', 'available') as $block)
              <li>{{ \Carbon\Carbon::parse($block->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($block->end_date)->format('M d, Y') }}</li>
            @endforeach
          </ul>
        </div>
      @else
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> Always available for rent
        </div>
      @endif
      
      @if($cloth->availabilityBlocks->where('type', 'blocked')->count() > 0)
        <div class="alert alert-warning">
          <strong>Blocked Dates:</strong>
          <ul class="mb-0 mt-2">
            @foreach($cloth->availabilityBlocks->where('type', 'blocked') as $block)
              <li>{{ \Carbon\Carbon::parse($block->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($block->end_date)->format('M d, Y') }}
                @if($block->reason)
                  <small class="text-muted">({{ $block->reason }})</small>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>
    
    <!-- Rental Date Selection -->
    <div class="rental-selection mt-4">
      <h5>📅 Select Rental Dates</h5>
      <div class="row">
        <div class="col-md-6">
          <label for="start_date" class="form-label">Start Date *</label>
          <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="col-md-6">
          <label for="end_date" class="form-label">End Date *</label>
          <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
      </div>
      <div class="rental-summary mt-3" id="rental-summary" style="display: none;">
        <div class="card">
          <div class="card-body">
            <h6>Rental Summary</h6>
            <div id="rental-details"></div>
            <div class="total-price mt-2">
              <strong>Total: ₹<span id="total-price">0</span></strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- More Like This -->
<section class="related">
  <h3>MORE LIKE THIS</h3>
  <div class="carousel">
    <button class="prev">←</button>
    <div class="carousel-items">
      <!-- You can loop similar products here if available -->
    </div>
    <button class="next">→</button>
  </div>
</section>
@endsection

@section('scripts')
<script src="../js/product.js"></script>
<script>
// Cloth data for calculations
const clothData = {
    id: {{ $cloth->id }},
    rentPrice: {{ $cloth->rent_price }},
    securityDeposit: {{ $cloth->security_deposit }},
    availableBlocks: @json($cloth->availabilityBlocks->where('type', 'available')),
    blockedBlocks: @json($cloth->availabilityBlocks->where('type', 'blocked')),
    isAlwaysAvailable: {{ $cloth->availabilityBlocks->where('type', 'available')->count() == 0 ? 'true' : 'false' }}
};
$(document).ready(function() {
    console.log('Product page loaded');
    console.log('Cart button found:', $('.add-to-cart-btn').length);
    
    // Load cart items on page load to check rented status
    loadCartItems();
    
    // Date selection handlers
    $('#start_date, #end_date').change(function() {
        validateAndCalculateRental();
    });
    
    // Ensure cart functionality works on this page
    $('.add-to-cart-btn').click(function(e) {
        e.preventDefault();
        console.log('Rent button clicked');
        
        const clothId = $(this).data('cloth-id');
        const $btn = $(this);
        const originalText = $btn.text();
        
        console.log('Cloth ID:', clothId);
        
        // Get rental dates and cost
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const daysDiff = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24));
        
        if (!startDate || !endDate) {
            showAlert('danger', 'Please select rental start and end dates');
            $btn.prop('disabled', true).html('<i class="bi bi-cart-plus me-2"></i>SELECT DATES TO RENT');
            return;
        }
        
        // Calculate rental cost (without security deposit)
        const rentCost = clothData.rentPrice * daysDiff;
        
        if (rentCost <= 0) {
            showAlert('danger', 'Please select valid rental dates to calculate cost');
            $btn.prop('disabled', true).html('<i class="bi bi-cart-plus me-2"></i>SELECT DATES TO RENT');
            return;
        }
        
        // Show loading state
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
        
        const requestData = {
            cloth_id: clothId,
            rental_start_date: startDate,
            rental_end_date: endDate,
            total_rental_cost: rentCost,
            rental_days: daysDiff,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        console.log('Sending data to cart:', requestData);
        
        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: requestData,
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    // Update cart count
                    updateCartCount(response.cartCount);
                    
                    // Show success message
                    showAlert('success', response.message);
                    
                    // Update all buttons for this item to "RENTED"
                    updateAllRentButtons(clothId, true);
                    
                    // Reload cart items to update the list
                    loadCartItems();
                    
                    // Update button state
                    $btn.prop('disabled', true).html('<i class="bi bi-check me-2"></i>RENTED');
                } else {
                    showAlert('danger', response.message);
                    $btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-2"></i>RENT NOW');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error status:', xhr.status);
                console.log('Error response:', xhr.responseText);
                console.log('Error details:', error);
                
                if (xhr.status === 401) {
                    // User not logged in, redirect to login
                    window.location.href = '/login';
                } else if (xhr.status === 422) {
                    // Validation error
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.errors) {
                            const errorMessages = Object.values(response.errors).flat().join(', ');
                            showAlert('danger', 'Validation error: ' + errorMessages);
                        } else {
                            showAlert('danger', 'Please check your input and try again.');
                        }
                    } catch (e) {
                        showAlert('danger', 'An error occurred. Please try again.');
                    }
                } else {
                    showAlert('danger', 'An error occurred. Please try again.');
                }
                $btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-2"></i>RENT NOW');
            }
        });
    });
});

// Date validation and rental calculation
function validateAndCalculateRental() {
    const startDate = $('#start_date').val();
    const endDate = $('#end_date').val();
    const $rentButton = $('.add-to-cart-btn');
    const $rentalSummary = $('#rental-summary');
    const $rentalDetails = $('#rental-details');
    const $totalPrice = $('#total-price');
    
    if (!startDate || !endDate) {
        $rentButton.prop('disabled', true).html('<i class="bi bi-cart-plus me-2"></i>SELECT DATES TO RENT');
        $rentalSummary.hide();
        return;
    }
    
    const start = new Date(startDate);
    const end = new Date(endDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Basic validation
    if (start < today) {
        showAlert('danger', 'Start date cannot be in the past');
        $rentButton.prop('disabled', true);
        $rentalSummary.hide();
        return;
    }
    
    if (end <= start) {
        showAlert('danger', 'End date must be after start date');
        $rentButton.prop('disabled', true);
        $rentalSummary.hide();
        return;
    }
    
    // Calculate number of days
    const daysDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    
    // Check availability
    if (!clothData.isAlwaysAvailable) {
        const isAvailable = checkAvailability(start, end);
        if (!isAvailable.available) {
            showAlert('danger', isAvailable.message);
            $rentButton.prop('disabled', true);
            $rentalSummary.hide();
            return;
        }
    }
    
    // Check blocked dates
    const blockedCheck = checkBlockedDates(start, end);
    if (!blockedCheck.available) {
        showAlert('danger', blockedCheck.message);
        $rentButton.prop('disabled', true);
        $rentalSummary.hide();
        return;
    }
    
    // Calculate prices
    const rentCost = clothData.rentPrice * daysDiff;
    const totalCost = rentCost + clothData.securityDeposit;
    
    // Update UI
    $rentalDetails.html(`
        <div class="row">
            <div class="col-6">Rental Period:</div>
            <div class="col-6">${daysDiff} days</div>
        </div>
        <div class="row">
            <div class="col-6">Daily Rate:</div>
            <div class="col-6">₹${clothData.rentPrice.toLocaleString()}</div>
        </div>
        <div class="row">
            <div class="col-6">Rental Cost:</div>
            <div class="col-6">₹${rentCost.toLocaleString()}</div>
        </div>
        <div class="row">
            <div class="col-6">Security Deposit:</div>
            <div class="col-6">₹${clothData.securityDeposit.toLocaleString()}</div>
        </div>
    `);
    
    $totalPrice.text(totalCost.toLocaleString());
    $rentalSummary.show();
    
    // Enable rent button
    $rentButton.prop('disabled', false).html('<i class="bi bi-cart-plus me-2"></i>RENT NOW - ₹' + totalCost.toLocaleString());
    
    // Clear any previous alerts
    $('.alert-danger').remove();
}

function checkAvailability(start, end) {
    const availableBlocks = clothData.availableBlocks;
    
    for (let block of availableBlocks) {
        const blockStart = new Date(block.start_date);
        const blockEnd = new Date(block.end_date);
        
        if (start >= blockStart && end <= blockEnd) {
            return { available: true };
        }
    }
    
    return { 
        available: false, 
        message: 'Selected dates are not within available rental periods' 
    };
}

function checkBlockedDates(start, end) {
    const blockedBlocks = clothData.blockedBlocks;
    
    for (let block of blockedBlocks) {
        const blockStart = new Date(block.start_date);
        const blockEnd = new Date(block.end_date);
        
        // Check if rental period overlaps with blocked period
        if ((start <= blockEnd && end >= blockStart)) {
            return { 
                available: false, 
                message: `Selected dates overlap with blocked period: ${blockStart.toLocaleDateString()} - ${blockEnd.toLocaleDateString()}` 
            };
        }
    }
    
    return { available: true };
}

// Load cart items and check rented status
function loadCartItems() {
    $.ajax({
        url: '/cart/items',
        type: 'GET',
        success: function(response) {
            if (response.cartItems) {
                window.cartItems = response.cartItems;
                checkRentedItems();
            }
        },
        error: function() {
            // If error, assume no items in cart
            window.cartItems = [];
        }
    });
}

// Update all rent buttons for a specific item
function updateAllRentButtons(clothId, isRented) {
    const buttons = $(`.add-to-cart-btn[data-cloth-id="${clothId}"]`);
    
    buttons.each(function() {
        const $btn = $(this);
        
        if (isRented) {
            $btn.text('RENTED')
                .addClass('btn-success')
                .removeClass('btn-warning')
                .prop('disabled', true)
                .attr('title', 'Already in cart');
        } else {
            $btn.html('<i class="bi bi-cart-plus me-2"></i>RENT NOW')
                .removeClass('btn-success')
                .addClass('btn-warning')
                .prop('disabled', false)
                .removeAttr('title');
        }
    });
}

// Check which items are already in cart and update buttons
function checkRentedItems() {
    if (!window.cartItems) return;
    
    window.cartItems.forEach(function(item) {
        updateAllRentButtons(item.cloth_id, true);
    });
}

// Update cart count in header
function updateCartCount(count) {
    const $cartCount = $('#cart-count');
    if ($cartCount.length > 0) {
        $cartCount.text(count);
        if (count > 0) {
            $cartCount.show();
        } else {
            $cartCount.hide();
        }
    }
}

// Show alert message
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert
    $('body').prepend(alertHtml);
    
    // Auto-hide after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
}
</script>
@endsection 