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

    // Route khusus Export/Import Supplier JSON
    Route::get('/supplier-export', [SupplierController::class, 'exportJson'])->name('supplier.export');
    Route::post('/supplier-import', [SupplierController::class, 'importJson'])->name('supplier.import');

    Route::resource('supplier', SupplierController::class)->names([
        'index'     => 'supplier.index',
        'store'     => 'supplier.store',
        'show'      => 'supplier.show',
        'update'    => 'supplier.update',
        'destroy'   => 'supplier.destroy'
    ])->except(['create', 'edit']);

    // Route khusus Export/Import Layup JSON
    Route::get('/supplier/{supplier}/layup-export', [CLTLayupController::class, 'exportJson'])->name('supplier.layup.export');
    Route::post('/supplier/{supplier}/layup-import', [CLTLayupController::class, 'importJson'])->name('supplier.layup.import');

    Route::resource('supplier.layup', CLTLayupController::class)->names([
        'store'     => 'supplier.layup.store',
        'show'      => 'supplier.layup.show',
        'edit'      => 'supplier.layup.edit',
        'update'    => 'supplier.layup.update',
        'destroy'   => 'supplier.layup.destroy'
    ])->shallow()->except(['index', 'create']);

    // Route khusus AJAX: simpan perubahan layers dari halaman edit
    Route::post('/layup/{layup}/layers/save', [CLTLayupController::class, 'saveLayers'])
        ->name('layup.layers.save');

});

require __DIR__.'/auth.php';
