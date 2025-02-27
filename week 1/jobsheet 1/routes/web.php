<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('welcome');
});
// digunakan untuk menampilkan halaman welcome

Route::resource('items', ItemController::class);
// digunakan untuk membuat route resource untuk item
