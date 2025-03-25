<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('/category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'foodBeverage']);
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth']);
    Route::get('/home-care', [ProductController::class, 'homeCare']);
    Route::get('/baby-kid', [ProductController::class, 'babyKid']);
});

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

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/user', [UserController::class, 'index']); // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']); // menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
});

// Tambahkan route grup untuk level
Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']); // Menampilkan halaman tabel level
    Route::post('/list', [LevelController::class, 'list']); // Mengambil data level untuk DataTables
    Route::get('/create', [LevelController::class, 'create']); // Menampilkan form tambah level
    Route::post('/', [LevelController::class, 'store']); // Menyimpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']); // Menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']); // Menampilkan form edit level
    Route::put('/{id}', [LevelController::class, 'update']); // Menyimpan perubahan level
    Route::delete('/{id}', [LevelController::class, 'destroy']); // Menghapus level
});

// Tambahkan route grup untuk barang
Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']); // Menampilkan halaman tabel kategori
    Route::post('/list', [KategoriController::class, 'list']); // Mengambil data kategori untuk DataTables
    Route::get('/create', [KategoriController::class, 'create']); // Menampilkan form tambah kategori
    Route::post('/', [KategoriController::class, 'store']); // Menyimpan data kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); // Menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // Menampilkan form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']); // Menyimpan perubahan kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // Menghapus kategori
});

// Tambahkan route grup untuk barang
Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']); // Menampilkan halaman tabel barang
    Route::post('/list', [BarangController::class, 'list']); // Mengambil data barang untuk DataTables
    Route::get('/create', [BarangController::class, 'create']); // Menampilkan form tambah barang
    Route::post('/', [BarangController::class, 'store']); // Menyimpan data barang baru
    Route::get('/{id}', [BarangController::class, 'show']); // Menampilkan detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); // Menampilkan form edit barang
    Route::put('/{id}', [BarangController::class, 'update']); // Menyimpan perubahan barang
    Route::delete('/{id}', [BarangController::class, 'destroy']); // Menghapus barang
});

// Tambahkan route grup untuk supplier
Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index']); // Menampilkan halaman tabel supplier
    Route::post('/list', [SupplierController::class, 'list']); // Mengambil data supplier untuk DataTables
    Route::get('/create', [SupplierController::class, 'create']); // Menampilkan form tambah supplier
    Route::post('/', [SupplierController::class, 'store']); // Menyimpan data supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); // Menampilkan detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); // Menampilkan form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update']); // Menyimpan perubahan supplier
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // Menghapus supplier
});