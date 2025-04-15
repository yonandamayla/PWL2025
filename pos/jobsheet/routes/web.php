<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Registration Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard access with role redirection
    Route::get('/', function() {
        if(auth()->user()->role_id == 3) {
            return redirect()->route('orders.create');
        }
        return app()->make(HomeController::class)->index();
    })->name('home');
    
    // Customer redirect after login 
    Route::redirect('/customer', '/orders/create')->name('customer.home');

    // Products and transactions
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/transaction/create', [TransactionController::class, 'create'])->name('transactions.create');

    // Basic routes
    Route::get('/role', [RoleController::class, 'index']);
    Route::get('/order', [OrderController::class, 'index']);
    Route::get('/order-item', [OrderItemController::class, 'index']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences');

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/list', [UserController::class, 'list'])->name('users.list');
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

    // Item management routes 
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/list', [ItemController::class, 'list'])->name('items.list');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Consolidated Transaction Management Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/list', [OrderController::class, 'getOrders'])->name('orders.list');
        // Process order (admin/cashier only)
        Route::post('/{id}/process', [OrderController::class, 'processOrder'])
            ->middleware('check.role:1,2')
            ->name('orders.process');
        // Update order status
        Route::post('/{id}/status', [OrderController::class, 'updateStatus'])
            ->middleware('role:1,2')
            ->name('orders.status.update');
        // Customer routes
        Route::get('/create', [OrderController::class, 'create'])
            ->middleware('role:3')  // Assuming 3=customer
            ->name('orders.create');
        Route::post('/', [OrderController::class, 'store'])
            ->middleware('role:3')
            ->name('orders.store');
    });
});