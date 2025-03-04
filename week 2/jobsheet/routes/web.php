<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PageController;

// Route::get('/hello', function () {
//     return ('hello');
// });

// Route::get('/about', function () {
//     return ('about');
// });

// // Route Parameters
// Route::get('/user/{yonandamayla}', function ($yonandamayla) {
//     return "Nama saya " . $yonandamayla;
// });

// Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {
//     return "Post ke- " . $postId . " Komentar ke-: " . $commentId;
// });

// Route::get('/articles/{id}', function ($id) {
//     return "Halaman ke- " . $id;
// });

// // Optional Parameters
// Route::get('/user/{yonandamayla?}', function ($yonandamayla=null) {
//     return "Nama saya " . $yonandamayla;
// });

// Route::get('/user/{name?}', function ($name = 'Yonanda Mayla') {
//     return "Nama saya " . $name;
// });

// Route Name
// Route::get('/user/profile', function () {
//     // 
// })->name('profile');

// Route::get (
//     '/user/profile', 
//     [UserProfileController::class, 'show']
// )->name('profile');
// // Generating URLs..
// $url = route('profile');
// // Generating Redirects..
// return redirect()->route('profile');

// Route Group dan Route Prefix
// Route::middleware(['first', 'second'])->group(function () {
//     Route::get('/', function () {
//         // Uses first & second Middleware..
//     });

//     Route::get('/user/profile', function () {
//         // Uses first & second Middleware..
//     });
// });

// Route::domain('{account}.example.com')->group(function () {
//     Route::get('user/{id}', function ($account, $id) {
//         //
//     });
// });

// Route::middleware('auth') -> group(function () {
//     Route::get('/user', [UserProfileController::class, 'index']);
//     Route::get('/post', [PostController::class, 'index']);
//     Route::get('/event', [EventController::class, 'index']);
// });

// Route Prefixes
// Route::prefix('admin')->group(function () {
//     Route::get('/user', [UserProfileController::class, 'index']);
//     Route::get('/post', [PostController::class, 'index']);
//     Route::get('/event', [EventController::class, 'index']);
// });

// Redirect Routes
// Route::redirect('/here', '/there',);

// // View Routes
// Route::view('/welcome', 'welcome');
// Route::view('/welcome', 'welcome', ['name' => 'Yonanda Mayla']);

// // Menambahkan route /hello
// Route::get('/hello', [WelcomeController::class, 'hello']);

// PageController
// Route::get('/', [PageController::class, 'index']);
// Route::get('/about', [PageController::class, 'about']);
// Route::get('/articles/{id}', [PageController::class, 'show']);
// Route::get('/articles', [PageController::class, 'articles']);

// AboutController, HomeController, ArticleController
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\AboutController;
// use App\Http\Controllers\ArticleController;

// Route::get('/', HomeController::class);
// Route::get('/about', AboutController::class);
// Route::get('/articles/{id?}', ArticleController::class);

// Resource Controller
use App\Http\Controllers\PhotoController;

// Route::resource('photos', PhotoController::class);

Route::resource('photos', PhotoController::class)->only([
    'index',
    'show'
]);
Route::resource('photos', PhotoController::class)->except([
    'create',
    'store',
    'update',
    'destroy'
]);
