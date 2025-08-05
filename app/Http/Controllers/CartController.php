<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Cloth;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $showFilters = false;
        $cartItems = [];
        
        if (Auth::check()) {
            $cartItems = Auth::user()->cartItems()->with('cloth.images')->get();
        }
        
        return view('cart', compact('showFilters', 'cartItems'));
    }

    /**
     * Add item to cart (AJAX)
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to add items to cart']);
        }

        // Log the incoming request for debugging
        \Log::info('Cart add request:', $request->all());

        $request->validate([
            'cloth_id' => 'required|exists:clothes,id',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after:rental_start_date',
            'total_rental_cost' => 'required|numeric|min:0',
            'rental_days' => 'required|integer|min:1',
        ]);

        $cloth = Cloth::findOrFail($request->cloth_id);
        
        // Check if item is already in cart
        $existingItem = Auth::user()->cartItems()->where('cloth_id', $request->cloth_id)->first();
        
        if ($existingItem) {
            // Update existing item with new rental dates
            $existingItem->update([
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'total_rental_cost' => $request->total_rental_cost,
                'rental_days' => $request->rental_days,
            ]);
            $message = 'Rental dates updated in cart';
        } else {
            Auth::user()->cartItems()->create([
                'cloth_id' => $request->cloth_id,
                'quantity' => 1,
                'rental_start_date' => $request->rental_start_date,
                'rental_end_date' => $request->rental_end_date,
                'total_rental_cost' => $request->total_rental_cost,
                'rental_days' => $request->rental_days,
            ]);
            $message = 'Item added to cart successfully';
        }

        $cartCount = Auth::user()->cartItems()->count();

        // Log the success response
        \Log::info('Cart item added successfully', [
            'cloth_id' => $request->cloth_id,
            'cart_count' => $cartCount,
            'message' => $message
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function removeFromCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to manage cart']);
        }

        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = Auth::user()->cartItems()->findOrFail($request->cart_item_id);
        $cartItem->delete();

        $cartCount = Auth::user()->cartItems()->count();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity (AJAX)
     */
    public function updateQuantity(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to manage cart']);
        }

        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cartItem = Auth::user()->cartItems()->findOrFail($request->cart_item_id);
        $cartItem->update(['quantity' => $request->quantity]);

        $cartCount = Auth::user()->cartItems()->count();

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Get cart count (AJAX)
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['cartCount' => 0]);
        }

        $cartCount = Auth::user()->cartItems()->count();
        return response()->json(['cartCount' => $cartCount]);
    }

    /**
     * Get cart items for checking rented status (AJAX)
     */
    public function getCartItems()
    {
        if (!Auth::check()) {
            return response()->json(['cartItems' => []]);
        }

        $cartItems = Auth::user()->cartItems()->with('cloth')->get();
        $items = $cartItems->map(function($item) {
            return [
                'cloth_id' => $item->cloth_id,
                'quantity' => $item->quantity
            ];
        });

        return response()->json(['cartItems' => $items]);
    }
}
