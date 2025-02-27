<?php

use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return view('hello');
});

Route::get('/about', function () {
    return ('about');
});

Route::get('/user/{yonandamayla}', function ($yonandamayla) {
    return "Nama saya " . $yonandamayla;
});

Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
    return "Post ke- " . $postId . " Komentar ke-: " . $commentId;
});

Route::get('/articles/{id}', function ($id) {
    return "Halaman ke- " . $id;
});
