<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// =======================
// Admin Controllers
// =======================
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\ServiceController as AdminService;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MechanicController;
use App\Http\Controllers\Admin\AdminController;

// =======================
// Customer Controllers
// =======================
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use App\Http\Controllers\ServiceController; // layanan umum
use App\Http\Controllers\Customer\ProductController as CustomerProduct;
use App\Http\Controllers\Customer\OrderController as CustomerOrder;
use App\Http\Controllers\Customer\VehicleController;
use App\Http\Controllers\Customer\CustomerController;

// =======================
// Mechanic Controllers
// =======================
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboard;

// =======================
// Middleware
// =======================
use App\Http\Middleware\IsAdmin;

// =======================
// Landing page
// =======================
Route::get('/', fn() => view('welcome'))->name('home');

// =======================
// Dashboard redirect sesuai role
// =======================
Route::middleware(['auth','verified'])->get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'mechanic' => redirect()->route('mechanic.dashboard'),
        default => redirect()->route('customer.dashboard'),
    };
})->name('dashboard');

// =======================
// Profile routes
// =======================
Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =======================
// Admin routes
// =======================
Route::middleware(['auth','verified', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('bookings', AdminBooking::class)->only(['index','show','update','destroy']);
        Route::resource('services', AdminService::class)->names('services');
        Route::resource('products', AdminProduct::class)->names('products');
        Route::patch('/products/{product}/update-stock', [AdminProduct::class, 'updateStock'])->name('products.updateStock');

        Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrder::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrder::class, 'updateStatus'])->name('orders.updateStatus');
        Route::delete('/orders/{order}', [AdminOrder::class, 'destroy'])->name('orders.destroy');

        Route::get('/penghasilan', [ReportController::class, 'penghasilan'])->name('penghasilan');
        Route::resource('mechanics', MechanicController::class)->names('mechanics');
    });

// =======================
// Customer routes
// =======================
Route::middleware(['auth','verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function() {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

        Route::get('/products', [CustomerProduct::class, 'index'])->name('products');
        Route::get('/products/{product}', [CustomerProduct::class, 'show'])->name('products.show');
        Route::get('/products/{product}/beli', [CustomerOrder::class, 'create'])->name('orders.create');
        Route::post('/products/{product}/beli', [CustomerOrder::class, 'store'])->name('orders.store');
        Route::get('/orders', [CustomerOrder::class, 'index'])->name('orders.index');

        Route::get('/booking', [CustomerBooking::class, 'index'])->name('booking.index');
        Route::get('/booking/create', [CustomerBooking::class, 'create'])->name('booking.create');
        Route::post('/booking', [CustomerBooking::class, 'store'])->name('booking.store');

        Route::resource('/vehicles', VehicleController::class)->names('vehicles');
        Route::get('/services', [ServiceController::class, 'index'])->name('services');
    });

// =======================
// Mechanic routes
// =======================
Route::prefix('mechanic')
    ->middleware(['auth','verified','role:mechanic']) // ✅ gunakan middleware role
    ->name('mechanic.')
    ->group(function () {
        Route::get('/dashboard', [MechanicDashboard::class, 'index'])->name('dashboard');
    });

// =======================
// Auth routes (Breeze default)
// =======================
require __DIR__.'/auth.php';