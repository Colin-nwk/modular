<?php

// use Modules\Foobar\Http\Controllers\FoobarController;

// Route::get('/foobars', [FoobarController::class, 'index'])->name('foobars.index');
// Route::get('/foobars/create', [FoobarController::class, 'create'])->name('foobars.create');
// Route::post('/foobars', [FoobarController::class, 'store'])->name('foobars.store');
// Route::get('/foobars/{foobar}', [FoobarController::class, 'show'])->name('foobars.show');
// Route::get('/foobars/{foobar}/edit', [FoobarController::class, 'edit'])->name('foobars.edit');
// Route::put('/foobars/{foobar}', [FoobarController::class, 'update'])->name('foobars.update');
// Route::delete('/foobars/{foobar}', [FoobarController::class, 'destroy'])->name('foobars.destroy');

use Illuminate\Support\Facades\Route;
use Modules\Foobar\Http\Controllers\BazController;

Route::get('/baz', [BazController::class, 'get'])->name('foobar.baz.get');
