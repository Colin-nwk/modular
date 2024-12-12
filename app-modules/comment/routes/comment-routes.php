<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\CommentController;

Route::middleware(['web', 'auth'])->group(
    function () {
        // Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
        // Route::get('/comments/create', [CommentController::class, 'create'])->name('comments.create');
        Route::post('/comments', [CommentController::class, 'store'])->name('comment.comment.store');
        // Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
        // Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
        // Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.comment.destroy');
    }
);
