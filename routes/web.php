<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    // Fitur POS (Bisa diakses siapa saja yang punya key 'pos')
    Route::middleware(['role:pos'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/print/{id}', [PosController::class, 'print'])->name('pos.print');
    });

    // Fitur Produk (Bisa diakses siapa saja yang punya key 'products')
    Route::middleware(['role:products'])->group(function () {
        Route::resource('products', ProductController::class);
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

    Route::middleware(['auth', 'role:root'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
    });

    Route::get('/', function () {
        return redirect('/dashboard');
    });
});