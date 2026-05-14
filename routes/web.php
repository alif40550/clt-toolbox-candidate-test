<?php

use App\Http\Controllers\CLTLayupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('supplier', SupplierController::class)->names([
        'index' => 'supplier.index',
        'store' => 'supplier.store',
        'show' => 'supplier.show',
        'update' => 'supplier.update',
        'destroy' => 'supplier.destroy'
    ])->except(['create', 'edit']);

    Route::resource('supplier.layup', CLTLayupController::class)->names([
        'store' => 'supplier.layup.store',
        'show' => 'supplier.layup.show',
        'update' => 'supplier.layup.update',
        'destroy' => 'supplier.layup.destroy'
    ])->shallow()->except(['index', 'create', 'edit']);

});

require __DIR__.'/auth.php';
