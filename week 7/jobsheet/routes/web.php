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
use App\Http\Controllers\AuthController;

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

// JB 7 Autentikasi
Route::pattern('id', '[0-9]+'); // Menambahkan pola untuk parameter id
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    // Tambahkan route grup untuk user yg memerlukan autentikasi disini
    Route::group(['prefix' => 'user'], function () {
        Route::get('/user', [UserController::class, 'index']); // menampilkan halaman awal user
        Route::post('/list', [UserController::class, 'list']); // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']); // menampilkan halaman form tambah user
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user dengan ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']); // menyimpan data user baru dengan ajax   
        Route::post('/', [UserController::class, 'store']); // menyimpan data user baru
        Route::get('/{id}', [UserController::class, 'show']); // menampilkan detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']); // menampilkan halaman form edit user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // menampilkan halaman form edit user dengan ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // menampilkan konfirmasi hapus user dengan ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // menghapus data user AJAX
        Route::put('/{id}', [UserController::class, 'update']); // menyimpan perubahan data user
        Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    });
});

Route::middleware(['auth'])->group(function () { //artinya semua route di dalam grup ini harus terautentikasi
    Route::get('/', [WelcomeController::class, 'index']);
    // route level

    // artinya semua route di dalam grup ini harus punya role ADM (admin)
    Route::middleware(['authorize:ADM'])->group(function() {
    // Tambahkan route grup untuk level
    Route::group(['prefix' => 'level'], function () {
        Route::get('/', [LevelController::class, 'index']); // Menampilkan halaman tabel level
        Route::post('/list', [LevelController::class, 'list']); // Mengambil data level untuk DataTables
        Route::get('/create', [LevelController::class, 'create']); // Menampilkan form tambah level
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan form tambah level ajax
        Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru ajax
        Route::post('/', [LevelController::class, 'store']); // Menyimpan data level baru
        Route::get('/{id}', [LevelController::class, 'show']); // Menampilkan detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']); // Menampilkan form edit level
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan form edit level ajax
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan level ajax
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Menampilkan konfirmasi hapus level ajax
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Menghapus level ajax
        Route::put('/{id}', [LevelController::class, 'update']); // Menyimpan perubahan level
        Route::delete('/{id}', [LevelController::class, 'destroy']); // Menghapus level
    });
    });
});

// Tambahkan route grup untuk kategori
Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']); // Menampilkan halaman tabel kategori
    Route::post('/list', [KategoriController::class, 'list']); // Mengambil data kategori untuk DataTables
    Route::get('/create', [KategoriController::class, 'create']); // Menampilkan form tambah kategori
    Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan form tambah kategori ajax
    Route::post('/ajax', [KategoriController::class, 'store_ajax']); // Menyimpan data kategori baru ajax
    Route::post('/', [KategoriController::class, 'store']); // Menyimpan data kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']); // Menampilkan detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']); // Menampilkan form edit kategori
    Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan form edit kategori ajax
    Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan kategori ajax
    Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Menampilkan konfirmasi hapus kategori ajax
    Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Menghapus kategori ajax
    Route::put('/{id}', [KategoriController::class, 'update']); // Menyimpan perubahan kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // Menghapus kategori
});

// Tambahkan route grup untuk barang
Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']); // menampilkan halaman awal barang
    Route::post("/list", [BarangController::class, 'list']); // menampilkan data barang dalam bentuk json untuk datatables
    Route::get('/create', [BarangController::class, 'create']); // menampilkan halaman form tambah barang
    Route::post('/', [BarangController::class, 'store']); // menyimpan data barang baru
    Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah barang Ajax
    Route::post('/ajax', [BarangController::class, 'store_ajax']); // Menyimpan data barang baru Ajax
    Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit barang Ajax
    Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data barang Ajax
    Route::get('/{id}', [BarangController::class, 'show']); // menampilkan detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit barang
    Route::put('/{id}', [BarangController::class, 'update']); // menyimpan perubahan data barang
    Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete barang Ajax
    Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data barang Ajax
    Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang
});

// Perbaikan route grup untuk supplier - hapus duplikat dan gunakan nama route yang konsisten
Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index']); // Menampilkan halaman tabel supplier
    Route::post('/list', [SupplierController::class, 'list']); // Mengambil data supplier untuk DataTables
    Route::get('/create', [SupplierController::class, 'create']); // Menampilkan form tambah supplier
    Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan form tambah supplier ajax
    Route::post('/store_ajax', [SupplierController::class, 'store_ajax']); // Menyimpan data supplier baru ajax
    Route::post('/', [SupplierController::class, 'store']); // Menyimpan data supplier baru
    Route::get('/{id}', [SupplierController::class, 'show']); // Menampilkan detail supplier
    Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); // Menampilkan detail supplier ajax
    Route::get('/{id}/edit', [SupplierController::class, 'edit']); // Menampilkan form edit supplier
    Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Menampilkan form edit supplier ajax
    Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan supplier ajax
    Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Menampilkan konfirmasi hapus supplier ajax
    Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Menghapus supplier ajax
    Route::put('/{id}', [SupplierController::class, 'update']); // Menyimpan perubahan supplier
    Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); // Menampilkan detail supplier ajax
    Route::delete('/{id}', [SupplierController::class, 'destroy']); // Menghapus supplier
});
