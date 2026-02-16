<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManualSaleController;
use App\Http\Controllers\MasterDataController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    // Fitur POS (Bisa diakses siapa saja yang punya key 'pos')
    Route::middleware(['role:pos'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/print/{id}', [PosController::class, 'print'])->name('pos.print');
        Route::get('/manual_sales', [ManualSaleController::class, 'index'])->name('manual_sales.index');
        Route::post('/manual_sales/store', [ManualSaleController::class, 'store'])->name('manual_sales.store');
    });

    // Fitur Produk (Bisa diakses siapa saja yang punya key 'products')
    Route::middleware(['role:products'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/products/{id}/barcode', [ProductController::class, 'printBarcode'])->name('products.barcode');

        Route::prefix('master')->group(function () {
            Route::get('/', [MasterDataController::class, 'index'])->name('master.index');
            
            // KATEGORI
            // Tambahkan rute GET ini untuk mencegah error 'MethodNotAllowed'
            Route::get('/category', function() { return redirect()->route('master.index'); });
            Route::post('/category', [MasterDataController::class, 'storeCategory'])->name('master.category.store');
            Route::delete('/category/{id}', [MasterDataController::class, 'destroyCategory'])->name('master.category.destroy');
            
            // MERK (BRAND)
            // Sama dengan kategori, tambahkan redirect untuk GET
            Route::get('/brand', function() { return redirect()->route('master.index'); });
            Route::post('/brand', [MasterDataController::class, 'storeBrand'])->name('master.brand.store');
            Route::delete('/brand/{id}', [MasterDataController::class, 'destroyBrand'])->name('master.brand.destroy');
        });
    });

    // Fitur Inventory (Bisa diakses siapa saja yang punya key 'inventory')
    Route::middleware(['role:inventory'])->group(function () {
        Route::resource('inventory', InventoryController::class);
    });

    // Fitur Dashboard & Reports (Bisa diakses siapa saja yang punya key 'dashboard')
    Route::middleware(['role:dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    route::middleware(['role:category'])->group(function(){
        Route::get('/', [MasterDataController::class, 'index'])->name('index');
        // Category Routes
        Route::post('/category', [MasterDataController::class, 'storeCategory'])->name('category.store');
        Route::put('/category/{id}', [MasterDataController::class, 'updateCategory'])->name('category.update');
        Route::delete('/category/{id}', [MasterDataController::class, 'destroyCategory'])->name('category.destroy');

        // Brand Routes
        Route::post('/brand', [MasterDataController::class, 'storeBrand'])->name('brand.store');
        Route::put('/brand/{id}', [MasterDataController::class, 'updateBrand'])->name('brand.update');
        Route::delete('/brand/{id}', [MasterDataController::class, 'destroyBrand'])->name('brand.destroy'); 
    });

    Route::middleware(['auth', 'role:root'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
    });

    Route::get('/', function () {
        return redirect('/dashboard');
    });
});