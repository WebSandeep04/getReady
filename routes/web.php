<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ClothController;

Route::get('/', [HomeController::class, 'index']);

// Registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Sell (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/sell', [SellController::class, 'showSellForm'])->name('sell');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});

// Admin
Route::get('/admin', [AdminController::class, 'index'])->name('admin');

// Admin Dashboard
Route::get('/admin/dashboard', function() {
    return view('admin.screens.dashboard');
})->name('admin.dashboard');

// User
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/admin/user/fetch', [UserController::class, 'fetch'])->name('user.fetch');
Route::post('/admin/user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');  

// Clothes Approval (Admin)
Route::get('/admin/clothes/fetch', [AdminController::class, 'fetchClothes'])->name('clothes.fetch');
Route::post('/admin/clothes/approve/{id}', [AdminController::class, 'approveCloth'])->name('clothes.approve');
Route::post('/admin/clothes/reject/{id}', [AdminController::class, 'rejectCloth'])->name('clothes.reject');  

// Dashboard stats (Admin)
Route::get('/admin/dashboard/stats', [AdminController::class, 'dashboardStats']);

// Frontend Management (Admin)
Route::get('/admin/frontend', [AdminController::class, 'frontend'])->name('admin.frontend');
Route::post('/admin/frontend/update', [AdminController::class, 'updateFrontendSetting'])->name('admin.frontend.update');
Route::get('/admin/frontend/settings/{section}', [AdminController::class, 'getFrontendSettings'])->name('admin.frontend.settings');  

// Category Management (Admin)
Route::get('/admin/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::post('/admin/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
Route::put('/admin/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
Route::delete('/admin/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');  
Route::get('/admin/categories/json', [App\Http\Controllers\CategoryController::class, 'json'])->name('categories.json');

// Fabric Type Management (Admin)
Route::get('/admin/fabric-types', [App\Http\Controllers\FabricTypeController::class, 'index'])->name('fabric_types.index');
Route::get('/admin/fabric-types/json', [App\Http\Controllers\FabricTypeController::class, 'json'])->name('fabric_types.json');
Route::post('/admin/fabric-types', [App\Http\Controllers\FabricTypeController::class, 'store'])->name('fabric_types.store');
Route::put('/admin/fabric-types/{id}', [App\Http\Controllers\FabricTypeController::class, 'update'])->name('fabric_types.update');
Route::delete('/admin/fabric-types/{id}', [App\Http\Controllers\FabricTypeController::class, 'destroy'])->name('fabric_types.destroy');

// Color Management (Admin)
Route::get('/admin/colors', [App\Http\Controllers\ColorController::class, 'index'])->name('colors.index');
Route::get('/admin/colors/json', [App\Http\Controllers\ColorController::class, 'json'])->name('colors.json');
Route::post('/admin/colors', [App\Http\Controllers\ColorController::class, 'store'])->name('colors.store');
Route::put('/admin/colors/{id}', [App\Http\Controllers\ColorController::class, 'update'])->name('colors.update');
Route::delete('/admin/colors/{id}', [App\Http\Controllers\ColorController::class, 'destroy'])->name('colors.destroy');

// Bottom Type Management (Admin)
Route::get('/admin/bottom-types', [App\Http\Controllers\BottomTypeController::class, 'index'])->name('bottom_types.index');
Route::get('/admin/bottom-types/json', [App\Http\Controllers\BottomTypeController::class, 'json'])->name('bottom_types.json');
Route::post('/admin/bottom-types', [App\Http\Controllers\BottomTypeController::class, 'store'])->name('bottom_types.store');
Route::put('/admin/bottom-types/{id}', [App\Http\Controllers\BottomTypeController::class, 'update'])->name('bottom_types.update');
Route::delete('/admin/bottom-types/{id}', [App\Http\Controllers\BottomTypeController::class, 'destroy'])->name('bottom_types.destroy');

// Size Management (Admin)
Route::get('/admin/sizes', [App\Http\Controllers\SizeController::class, 'index'])->name('sizes.index');
Route::get('/admin/sizes/json', [App\Http\Controllers\SizeController::class, 'json'])->name('sizes.json');
Route::post('/admin/sizes', [App\Http\Controllers\SizeController::class, 'store'])->name('sizes.store');
Route::put('/admin/sizes/{id}', [App\Http\Controllers\SizeController::class, 'update'])->name('sizes.update');
Route::delete('/admin/sizes/{id}', [App\Http\Controllers\SizeController::class, 'destroy'])->name('sizes.destroy');

// Body Type Fit Management (Admin)
Route::get('/admin/body-type-fits', [App\Http\Controllers\BodyTypeFitController::class, 'index'])->name('body_type_fits.index');
Route::get('/admin/body-type-fits/json', [App\Http\Controllers\BodyTypeFitController::class, 'json'])->name('body_type_fits.json');
Route::post('/admin/body-type-fits', [App\Http\Controllers\BodyTypeFitController::class, 'store'])->name('body_type_fits.store');
Route::put('/admin/body-type-fits/{id}', [App\Http\Controllers\BodyTypeFitController::class, 'update'])->name('body_type_fits.update');
Route::delete('/admin/body-type-fits/{id}', [App\Http\Controllers\BodyTypeFitController::class, 'destroy'])->name('body_type_fits.destroy');

// Garment Condition Management (Admin)
Route::get('/admin/garment-conditions', [App\Http\Controllers\GarmentConditionController::class, 'index'])->name('garment_conditions.index');
Route::get('/admin/garment-conditions/json', [App\Http\Controllers\GarmentConditionController::class, 'json'])->name('garment_conditions.json');
Route::post('/admin/garment-conditions', [App\Http\Controllers\GarmentConditionController::class, 'store'])->name('garment_conditions.store');
Route::put('/admin/garment-conditions/{id}', [App\Http\Controllers\GarmentConditionController::class, 'update'])->name('garment_conditions.update');
Route::delete('/admin/garment-conditions/{id}', [App\Http\Controllers\GarmentConditionController::class, 'destroy'])->name('garment_conditions.destroy');

// Product Page
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/clothes/{id}', [App\Http\Controllers\ClothController::class, 'show'])->name('clothes.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Cart AJAX routes (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
});

// Get cart count (for header)
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Get cart items (for checking rented status)
Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');

// Notifications (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// Listed Clothes (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/listed-clothes', [ClothController::class, 'index'])->name('listed.clothes');
    Route::get('/listed-clothes/{id}/edit', [ClothController::class, 'edit'])->name('listed.clothes.edit');
    Route::put('/listed-clothes/{id}', [ClothController::class, 'update'])->name('listed.clothes.update');
    Route::delete('/listed-clothes/{id}', [ClothController::class, 'destroy'])->name('listed.clothes.destroy');
    Route::delete('/listed-clothes/images/{imageId}', [ClothController::class, 'destroyImage'])->name('listed.clothes.images.destroy');
    
   
});