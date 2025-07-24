<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleAndPermissionController;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('contact')->group(function () {
        Route::get('/all', [ContactController::class, 'index'])->name('contact.index');
        Route::get('/data', [ContactController::class, 'renderContact'])->name('contact.data');
        Route::get('/create', [ContactController::class, 'create'])->name('contact.create');
        Route::get('/detail/{id}', [ContactController::class, 'show'])->name('contact.detail');
        Route::post('/store', [ContactController::class, 'store'])->name('contact.store');
        Route::post('/update', [ContactController::class, 'update'])->name('contact.update');
        Route::delete('/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
        Route::get('master-data/{id}', [ContactController::class,'masterData'])->name('contact.master-data');
        Route::post('merge-contact', [ContactController::class,'mergeContacts'])->name('contact.merge');
    });

    Route::resource('role-permission', RoleAndPermissionController::class)->middleware('permission:Role Management');
    //Route::resource('role-permission', RoleAndPermissionController::class);
    Route::get('add-permission', [RoleAndPermissionController::class,'addPermissions'])->name('user');
    Route::get('add-permission/data', [RoleAndPermissionController::class,'rolePermissionsData'])->name('role-permission.data');


});

require __DIR__ . '/auth.php';
