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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('/category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'foodBeverage']);
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth']);
    Route::get('/home-care', [ProductController::class, 'homeCare']);
    Route::get('/baby-kid', [ProductController::class, 'babyKid']);
});
Route::get('/user/{id}/name/{name}', [ProfileController::class, 'index']);
Route::get('/transaction', [TransactionController::class, 'index']);

Route::get('/role', [RoleController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);
Route::get('/item-type', [ItemTypeController::class, 'index']);
Route::get('/item', [ItemController::class, 'index']);
Route::get('/order', [OrderController::class, 'index']);
Route::get('/order-item', [OrderItemController::class, 'index']);

