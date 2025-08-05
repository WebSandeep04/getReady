@extends('layouts.app')

@section('title', 'Get Ready - Cart')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-warning mb-4">
                <i class="bi bi-cart3 me-2"></i>
                Shopping Cart
            </h2>
        </div>
    </div>

    <div class="cart-container">
        @if(Auth::check() && $cartItems->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <!-- Cart Items -->
                    @foreach($cartItems as $cartItem)
                        <div class="card mb-3 cart-item" data-cart-item-id="{{ $cartItem->id }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($cartItem->cloth->images->count() > 0)
                                            <img src="{{ asset('storage/' . $cartItem->cloth->images->first()->image_path) }}" 
                                                 alt="{{ $cartItem->cloth->title }}" class="img-fluid rounded">
                                        @else
                                            <img src="images/1.jpg" alt="{{ $cartItem->cloth->title }}" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="card-title">{{ $cartItem->cloth->title }}</h5>
                                        <p class="card-text text-muted">
                                            <small>Size: {{ $cartItem->cloth->size }}</small><br>
                                            <small>Condition: {{ $cartItem->cloth->condition }}</small>
                                        </p>
                                        <p class="text-warning fw-bold item-price" data-price="{{ $cartItem->cloth->rent_price }}" data-deposit="{{ $cartItem->cloth->security_deposit }}">
                                            ₹{{ number_format($cartItem->cloth->rent_price) }} <small class="text-muted">(per day)</small>
                                        </p>
                                        
                                        @if($cartItem->rental_start_date && $cartItem->rental_end_date)
                                            <div class="rental-info">
                                                <p class="mb-1">
                                                    <strong>Rental Period:</strong><br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($cartItem->rental_start_date)->format('M d, Y') }} - 
                                                        {{ \Carbon\Carbon::parse($cartItem->rental_end_date)->format('M d, Y') }}
                                                    </small>
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Duration:</strong> {{ $cartItem->rental_days }} days
                                                </p>
                                                <p class="mb-1">
                                                    <strong>Total Cost:</strong> ₹{{ number_format($cartItem->total_rental_cost) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="mb-2">
                                                <label for="quantity-{{ $cartItem->id }}" class="form-label">Quantity:</label>
                                                <input type="number" 
                                                       id="quantity-{{ $cartItem->id }}" 
                                                       class="form-control quantity-input" 
                                                       value="{{ $cartItem->quantity }}" 
                                                       min="1" 
                                                       max="10"
                                                       data-cart-item-id="{{ $cartItem->id }}">
                                            </div>
                                                                                         <p class="fw-bold item-total">
                                                 ₹{{ number_format($cartItem->total_rental_cost ?? ($cartItem->cloth->rent_price * $cartItem->quantity)) }}
                                             </p>
                                             <small class="text-muted">
                                                 @if($cartItem->rental_days)
                                                     ({{ $cartItem->rental_days }} days)
                                                 @else
                                                     (per day)
                                                 @endif
                                             </small>
                                            <button class="btn btn-outline-danger btn-sm remove-from-cart-btn" 
                                                    data-cart-item-id="{{ $cartItem->id }}">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="col-lg-4">
                    <!-- Cart Summary -->
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">Cart Summary</h5>
                        </div>
                        <div class="card-body">
                                                         @php
                                 $subtotal = $cartItems->sum(function($item) {
                                     return $item->total_rental_cost ?? ($item->cloth->rent_price * $item->quantity);
                                 });
                                 $total = $subtotal;
                             @endphp
                            
                            <div class="d-flex justify-content-between mb-2">
                                                        <span>Rental Cost:</span>
                                <span class="fw-bold subtotal-amount">₹{{ number_format($subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Security Deposit:</span>
                                <span class="fw-bold security-deposit-amount">₹{{ number_format($cartItems->sum(function($item) { return $item->cloth->security_deposit * $item->quantity; })) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="h5">Total:</span>
                                <span class="h5 text-warning total-amount">₹{{ number_format($total + $cartItems->sum(function($item) { return $item->cloth->security_deposit * $item->quantity; })) }}</span>
                            </div>
                            
                            <button class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-credit-card me-2"></i>
                                Proceed to Checkout
                            </button>
                            
                            <a href="/" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-2"></i>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-cart3 text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Your cart is empty</h4>
                <p class="text-muted">Add some items to your cart to get started!</p>
                <a href="/" class="btn btn-warning">
                    <i class="bi bi-arrow-left me-2"></i>
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.quantity-input {
    width: 80px;
}

.item-total {
    color: #ffc107;
}

.rental-info {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    border-left: 3px solid #ffc107;
}

.rental-info p {
    margin-bottom: 5px;
}

.rental-info strong {
    color: #495057;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Update quantity functionality
    $('.quantity-input').change(function() {
        const cartItemId = $(this).data('cart-item-id');
        const quantity = parseInt($(this).val());
        const $input = $(this);
        const $item = $(this).closest('.cart-item');
        
        // Update item total immediately
        updateItemTotal(cartItemId);
        
        // Update cart totals
        updateCartTotals();
        
        $.ajax({
            url: '/cart/update-quantity',
            type: 'POST',
            data: {
                cart_item_id: cartItemId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    updateCartCount(response.cartCount);
                    showAlert('success', response.message);
                }
            },
            error: function() {
                showAlert('danger', 'An error occurred. Please try again.');
                // Reset to original value
                $input.val($input.data('original-value'));
                // Recalculate totals
                updateCartTotals();
            }
        });
    });

    // Remove from cart functionality
    $('.remove-from-cart-btn').click(function(e) {
        e.preventDefault();
        
        const cartItemId = $(this).data('cart-item-id');
        const $item = $(this).closest('.cart-item');
        
        if (confirm('Are you sure you want to remove this item from cart?')) {
            $.ajax({
                url: '/cart/remove',
                type: 'POST',
                data: {
                    cart_item_id: cartItemId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        updateCartCount(response.cartCount);
                        
                        // Remove item from DOM
                        $item.fadeOut(function() {
                            $(this).remove();
                            
                            // Update cart totals
                            updateCartTotals();
                            
                            // Check if cart is empty
                            if ($('.cart-item').length === 0) {
                                $('.cart-container').html('<div class="text-center py-5"><h5>Your cart is empty</h5><a href="/" class="btn btn-warning">Continue Shopping</a></div>');
                            }
                        });
                        
                        showAlert('success', response.message);
                    }
                },
                error: function() {
                    showAlert('danger', 'An error occurred. Please try again.');
                }
            });
        }
    });

    // Initialize quantity inputs
    $('.quantity-input').each(function() {
        $(this).data('original-value', $(this).val());
    });
});

// Update item total price
function updateItemTotal(cartItemId) {
    const $item = $(`.cart-item[data-cart-item-id="${cartItemId}"]`);
    const quantity = parseInt($item.find('.quantity-input').val());
    
    // Use rental cost if available, otherwise calculate from daily rate
    const rentalInfo = $item.find('.rental-info');
    if (rentalInfo.length > 0) {
        const totalCostText = rentalInfo.find('p:last').text();
        const totalCost = parseFloat(totalCostText.replace(/[^\d.]/g, ''));
        const total = totalCost * quantity;
        $item.find('.item-total').text('₹' + total.toFixed(2));
    } else {
        const price = parseFloat($item.find('.item-price').data('price'));
        const total = quantity * price;
        $item.find('.item-total').text('₹' + total.toFixed(2));
    }
}

// Update cart totals
function updateCartTotals() {
    let rentalCost = 0;
    let securityDeposit = 0;
    
    $('.cart-item').each(function() {
        const $item = $(this);
        const quantity = parseInt($item.find('.quantity-input').val());
        const deposit = parseFloat($item.find('.item-price').data('deposit') || 0);
        
        // Use rental cost if available, otherwise calculate from daily rate
        const rentalInfo = $item.find('.rental-info');
        if (rentalInfo.length > 0) {
            const totalCostText = rentalInfo.find('p:last').text();
            const totalCost = parseFloat(totalCostText.replace(/[^\d.]/g, ''));
            rentalCost += totalCost * quantity;
        } else {
            const price = parseFloat($item.find('.item-price').data('price'));
            rentalCost += price * quantity;
        }
        
        securityDeposit += deposit * quantity;
    });
    
    const total = rentalCost + securityDeposit;
    
    // Update display
    $('.subtotal-amount').text('₹' + rentalCost.toFixed(2));
    $('.security-deposit-amount').text('₹' + securityDeposit.toFixed(2));
    $('.total-amount').text('₹' + total.toFixed(2));
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
