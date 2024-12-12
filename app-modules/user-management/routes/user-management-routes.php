<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserManagemtController;

// use Modules\UserManagement\Http\Controllers\UserManagementController;

// Route::get('/user-managements', [UserManagementController::class, 'index'])->name('user-managements.index');
// Route::get('/user-managements/create', [UserManagementController::class, 'create'])->name('user-managements.create');
// Route::post('/user-managements', [UserManagementController::class, 'store'])->name('user-managements.store');
// Route::get('/user-managements/{user-management}', [UserManagementController::class, 'show'])->name('user-managements.show');
// Route::get('/user-managements/{user-management}/edit', [UserManagementController::class, 'edit'])->name('user-managements.edit');
// Route::put('/user-managements/{user-management}', [UserManagementController::class, 'update'])->name('user-managements.update');
// Route::delete('/user-managements/{user-management}', [UserManagementController::class, 'destroy'])->name('user-managements.destroy');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/user-managements/{user}/add-role', [UserManagemtController::class, 'addRole']);
    Route::post('/user-managements/{user}/remove-role', [UserManagemtController::class, 'removeRole']);
    Route::post('/user-managements/{user}/give-permission', [UserManagemtController::class, 'givePermission']);
    Route::post('/user-managements/{user}/revoke-permission', [UserManagemtController::class, 'revokePermission']);
});
