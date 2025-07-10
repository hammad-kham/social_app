<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //posts routes
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::post('/like/{post}', [PostController::class, 'like'])->name('posts.like');
    Route::get('/posts/{post}/comments', [PostController::class, 'fetchAllComments']);
    Route::post('/comments', [PostController::class, 'storeComment'])->name('comments.store');
});



require __DIR__ . '/auth.php';
