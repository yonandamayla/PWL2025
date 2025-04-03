<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;

// JB 7 Autentikasi
Route::pattern('id', '[0-9]+'); // Menambahkan pola untuk parameter id
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes for registration
Route::get('/register', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

// Dashboard yang dapat diakses oleh semua role yang login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('dashboard');
});

Route::middleware(['authorize:ADM'])->group(function () {
    // Tambahkan route grup untuk user yg memerlukan autentikasi disini
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']); // menampilkan halaman awal user
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
        Route::get('/import', [UserController::class, 'import']); // Loads the form
        Route::post('/import_ajax', [UserController::class, 'import_ajax']); // Handles the upload
        Route::get('/export_excel', [UserController::class, 'export_excel']); // ajax form download excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']); // ajax form download pdf
    });
});

Route::middleware(['authorize:ADM,MNG'])->group(function () {
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
        Route::get('/import', [LevelController::class, 'import']); // Show import form
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // Handle file upload
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // ajax form download excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // ajax form download pdf
        Route::put('/{id}', [LevelController::class, 'update']); // Menyimpan perubahan level
        Route::delete('/{id}', [LevelController::class, 'destroy']); // Menghapus level
    });
});

Route::middleware(['authorize:ADM,MNG'])->group(function () {
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
        Route::get('/import', [KategoriController::class, 'import']); // Show import form
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); // Handle file upload
        Route::get('/export_excel', [KategoriController::class, 'export_excel']); // ajax form download excel
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); // ajax form download pdf
        Route::put('/{id}', [KategoriController::class, 'update']); // Menyimpan perubahan kategori
        Route::delete('/{id}', [KategoriController::class, 'destroy']); // Menghapus kategori
    });
});

Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
    // Akses read-only staf untuk barang
    Route::group(['prefix' => 'barang'], function () {
        Route::get('/', [BarangController::class, 'index']); // menampilkan halaman awal barang
        Route::post("/list", [BarangController::class, 'list']); // menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/{id}', [BarangController::class, 'show']); // menampilkan detail barang
    });
});

// Route untuk Admin dan Manager saja (create, update, delete barang)
Route::middleware(['authorize:ADM,MNG'])->group(function () {
    // Akses tambahan untuk barang (create, update, delete)
    Route::group(['prefix' => 'barang'], function () {
        Route::get('/create', [BarangController::class, 'create']); // menampilkan halaman form tambah barang
        Route::post('/', [BarangController::class, 'store']); // menyimpan data barang baru
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah barang Ajax
        Route::post('/ajax', [BarangController::class, 'store_ajax']); // Menyimpan data barang baru Ajax
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit barang Ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data barang Ajax
        Route::get('/{id}/edit', [BarangController::class, 'edit']); // menampilkan halaman form edit barang
        Route::put('/{id}', [BarangController::class, 'update']); // menyimpan perubahan data barang
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete barang Ajax
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data barang Ajax
        Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang
        Route::get('/import', [BarangController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // AJAX import excel
        Route::get('/export_excel', [BarangController::class, 'export_excel']); // ajax form download excel
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']); // ajax form download pdf
    });
});

Route::middleware(['authorize:ADM,MNG'])->group(function () {
    // Tambahkan route grup untuk supplier
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
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
        Route::put('/{id}', [SupplierController::class, 'update']); // Menyimpan perubahan supplier
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); // Menampilkan detail supplier ajax
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // Menghapus supplier
        Route::get('/import', [SupplierController::class, 'import']); // Loads the import form
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); // Handles the file upload
        Route::get('/export_excel', [SupplierController::class, 'export_excel']); // ajax form download excel
    });
});
