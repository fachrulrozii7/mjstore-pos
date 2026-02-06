<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;

Route::resource('inventory', InventoryController::class);
Route::resource('products', ProductController::class);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});
