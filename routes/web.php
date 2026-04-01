<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/halaman2', function () {
    return view('hal2');
});
Route::get('/halaman3', function () {
    return view('hal3');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    });
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// High privilege routes
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('distributor', DistributorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchase', PurchaseController::class);
});