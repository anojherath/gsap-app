<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\SeedOrderController;
use App\Http\Controllers\Farmer\SeedOrderController as FarmerSeedOrderController;
use App\Http\Controllers\FertilizerOrderController;
use App\Http\Controllers\Farmer\FertilizerOrderController as FarmerFertilizerOrderController;
use App\Http\Controllers\Farmer\HarvestOrderController;
use App\Http\Controllers\HarvestBuyer\HarvestOrderController as HarvestBuyerHarvestOrderController;

// ------------------------------
// Public Routes
// ------------------------------
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ------------------------------
// Admin Routes
// ------------------------------
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.admin');
    })->name('dashboard.admin');

    // User Registration
    Route::get('/user-registration', [UserController::class, 'index'])->name('admin.user_registration');
    Route::get('/user-registration/create', [UserController::class, 'create'])->name('admin.user_registration.create');
    Route::post('/user-registration', [UserController::class, 'store'])->name('admin.user_registration.store');
    Route::get('/user-registration/{id}/edit', [UserController::class, 'edit'])->name('admin.user_registration.edit');
    Route::put('/user-registration/{id}', [UserController::class, 'update'])->name('admin.user_registration.update');
    Route::delete('/user-registration/{id}', [UserController::class, 'destroy'])->name('admin.user_registration.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/users', [ReportController::class, 'userReport'])->name('admin.reports.users');
    Route::get('/reports/users/export/excel', [ReportController::class, 'exportExcel'])->name('admin.reports.users.export.excel');
    Route::get('/reports/users/export/pdf', [ReportController::class, 'exportPDF'])->name('admin.reports.users.export.pdf');
    Route::get('/reports/customers', [ReportController::class, 'customerReport'])->name('admin.reports.customers');
    Route::get('/reports/customers/export/excel', [ReportController::class, 'exportCustomerExcel'])->name('admin.reports.customers.export.excel');
    Route::get('/reports/customers/export/pdf', [ReportController::class, 'exportCustomerPDF'])->name('admin.reports.customers.export.pdf');

    // Notifications
    Route::get('/push-notification', [NotificationController::class, 'index'])->name('admin.push_notification');
    Route::post('/push-notification', [NotificationController::class, 'send'])->name('admin.push_notification.send');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// ------------------------------
// Seed Provider Routes
// ------------------------------
Route::middleware('auth')->prefix('seed-provider')->group(function () {
    Route::get('/seed-orders', [SeedOrderController::class, 'index'])->name('seed_orders.index');
    Route::get('/seed-orders/create', [SeedOrderController::class, 'create'])->name('seed_orders.create');
    Route::post('/seed-orders', [SeedOrderController::class, 'store'])->name('seed_orders.store');
    Route::get('/seed-orders/rejected', [SeedOrderController::class, 'rejected'])->name('seed_orders.rejected');
    Route::get('/seed-orders/{id}/edit', [SeedOrderController::class, 'edit'])->name('seed_orders.edit');
    Route::put('/seed-orders/{id}', [SeedOrderController::class, 'update'])->name('seed_orders.update');
    Route::delete('/seed-orders/{id}', [SeedOrderController::class, 'destroy'])->name('seed_orders.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboards.seed_provider');
    })->name('dashboard.seed_provider');
});

// ------------------------------
// Fertilizer Provider Routes
// ------------------------------
Route::middleware('auth')->prefix('fertilizer-provider')->group(function () {
    Route::get('/fertilizer-orders', [FertilizerOrderController::class, 'index'])->name('fertilizer_orders.index');
    Route::get('/fertilizer-orders/create', [FertilizerOrderController::class, 'create'])->name('fertilizer_orders.create');
    Route::post('/fertilizer-orders', [FertilizerOrderController::class, 'store'])->name('fertilizer_orders.store');
    Route::get('/fertilizer-orders/rejected', [FertilizerOrderController::class, 'rejected'])->name('fertilizer_orders.rejected');
    Route::get('/fertilizer-orders/{id}/edit', [FertilizerOrderController::class, 'edit'])->name('fertilizer_orders.edit');
    Route::put('/fertilizer-orders/{id}', [FertilizerOrderController::class, 'update'])->name('fertilizer_orders.update');
    Route::delete('/fertilizer-orders/{id}', [FertilizerOrderController::class, 'destroy'])->name('fertilizer_orders.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboards.fertilizer_provider');
    })->name('dashboard.fertilizer_provider');
});

// ------------------------------
// Agro-Chemical Provider & Others
// ------------------------------
Route::middleware('auth')->group(function () {
    Route::view('/agro-chemical-provider/dashboard', 'dashboards.agro_chemical_provider')->name('dashboard.agro_chemical_provider');
    Route::view('/harvest-buyer/dashboard', 'dashboards.harvest_buyer')->name('dashboard.harvest_buyer');
    Route::view('/farmer/dashboard', 'dashboards.farmer')->name('dashboard.farmer');
});

// ------------------------------
// Farmer Routes
// ------------------------------
Route::middleware('auth')->prefix('farmer')->group(function () {
    // Seed Orders
    Route::get('/seed-orders', [FarmerSeedOrderController::class, 'index'])->name('farmer.seed_orders.index');
    Route::post('/seed-orders/{id}/confirm', [FarmerSeedOrderController::class, 'confirm'])->name('farmer.seed_orders.confirm');
    Route::post('/seed-orders/{id}/reject', [FarmerSeedOrderController::class, 'reject'])->name('farmer.seed_orders.reject');

    // Fertilizer Orders
    Route::get('/fertilizer-orders', [FarmerFertilizerOrderController::class, 'index'])->name('farmer.fertilizer_orders.index');
    Route::post('/fertilizer-orders/{id}/confirm', [FarmerFertilizerOrderController::class, 'confirm'])->name('farmer.fertilizer_orders.confirm');
    Route::post('/fertilizer-orders/{id}/reject', [FarmerFertilizerOrderController::class, 'reject'])->name('farmer.fertilizer_orders.reject');

    // Harvest Orders
    Route::get('/harvest-orders', [HarvestOrderController::class, 'index'])->name('farmer.harvest_orders.index');
    Route::get('/harvest-orders/rejected', [HarvestOrderController::class, 'rejected'])->name('farmer.harvest_orders.rejected');
    Route::get('/harvest-orders/create', [HarvestOrderController::class, 'create'])->name('farmer.harvest_orders.create');
    Route::post('/harvest-orders', [HarvestOrderController::class, 'store'])->name('farmer.harvest_orders.store');

    // Add Edit, Update & Delete for rejected harvest orders
    Route::get('/harvest-orders/{id}/edit', [HarvestOrderController::class, 'edit'])->name('farmer.harvest_orders.edit');
    Route::put('/harvest-orders/{id}', [HarvestOrderController::class, 'update'])->name('farmer.harvest_orders.update');
    Route::delete('/harvest-orders/{id}', [HarvestOrderController::class, 'destroy'])->name('farmer.harvest_orders.destroy');
});

// ------------------------------
// Harvest Buyer Routes (NEW)
// ------------------------------
Route::middleware('auth')->prefix('harvest-buyer')->group(function () {
    Route::get('/orders', [HarvestBuyerHarvestOrderController::class, 'index'])->name('harvest_buyer.orders.index');
    Route::post('/orders/{id}/accept', [HarvestBuyerHarvestOrderController::class, 'accept'])->name('buyer.harvest_orders.accept');
    Route::post('/orders/{id}/reject', [HarvestBuyerHarvestOrderController::class, 'reject'])->name('buyer.harvest_orders.reject');
});
