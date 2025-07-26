<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;

// Public routes
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

// Admin routes
Route::prefix('admin')->middleware('auth')->group(function () {
    // User registration
    Route::get('/user-registration', [UserController::class, 'index'])->name('admin.user_registration');
    Route::get('/user-registration/create', [UserController::class, 'create'])->name('admin.user_registration.create');
    Route::post('/user-registration', [UserController::class, 'store'])->name('admin.user_registration.store');
    Route::get('/user-registration/{id}/edit', [UserController::class, 'edit'])->name('admin.user_registration.edit');
    Route::put('/user-registration/{id}', [UserController::class, 'update'])->name('admin.user_registration.update');
    Route::delete('/user-registration/{id}', [UserController::class, 'destroy'])->name('admin.user_registration.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');

    // Push notifications
    Route::get('/push-notification', [NotificationController::class, 'index'])->name('admin.push_notification');
    Route::post('/push-notification', [NotificationController::class, 'send'])->name('admin.push_notification.send');

    // Notification management routes
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
