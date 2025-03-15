<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\view;
// return view::make ('hello', ['name' => 'Andi']);
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('/category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'foodBeverage']);
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth']);
    Route::get('/home-care', [ProductController::class, 'homeCare']);
    Route::get('/baby-kid', [ProductController::class, 'babyKid']);
});

// josbheet 4
Route::get('/user/{id}/name/{name}', [ProfileController::class, 'index']);

Route::get('/transaction', [TransactionController::class, 'index']);
Route::get('/level', [LevelController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/user', [UserController::class, 'index']);

Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::delete('/user/hapus/{id}', [UserController::class, 'hapus']);

// jobsheet 5
// Route::get('/hello', function(){
//     return view ('blog.hello', ['name' => 'Andi']);
// });
Route::get('/hello', [WelcomeController::class, 'hello']);