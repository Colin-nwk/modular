<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PostController;


Route::get('/posts', [PostController::class, 'index'])->name('post.post.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('post.post.create');
Route::post('/posts', [PostController::class, 'store'])->name('post.post.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('post.post.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('post.post.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('post.post.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.post.destroy');
