<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes by role
Route::middleware('auth')->group(function () {
    Route::view('/admin/dashboard', 'dashboards.admin')->name('dashboard.admin');
    Route::view('/farmer/dashboard', 'dashboards.farmer')->name('dashboard.farmer');
    Route::view('/seed-provider/dashboard', 'dashboards.seed_provider')->name('dashboard.seed_provider');
    Route::view('/fertilizer-provider/dashboard', 'dashboards.fertilizer_provider')->name('dashboard.fertilizer_provider');
    Route::view('/agro-chemical-provider/dashboard', 'dashboards.agro_chemical_provider')->name('dashboard.agro_chemical_provider');
    Route::view('/harvest-buyer/dashboard', 'dashboards.harvest_buyer')->name('dashboard.harvest_buyer');
});

// Admin subroutes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/user-registration', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user_registration');
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports');
    Route::get('/push-notification', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.push_notification');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/user-registration', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.user_registration');
    Route::post('/user-registration', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.user_registration.store');
    // ... other routes
});
