<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

// =======================
// Customer Controllers
// =======================
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use App\Http\Controllers\Customer\ProductController as CustomerProduct;
use App\Http\Controllers\Customer\OrderController as CustomerOrder;
use App\Http\Controllers\Customer\VehicleController;
use App\Http\Controllers\Customer\BookingQueueController;
use App\Http\Controllers\PaymentController as CustomerPaymentController;

// =======================
// Mechanic Controllers
// =======================
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboard;
use App\Http\Controllers\Mechanic\JobController as MechanicJobController;

// =======================
// Other Controllers
// =======================
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController; // Ensure this controller exists
use App\Http\Middleware\IsAdmin;

// ============================================================
// LANDING PAGE
// ============================================================
Route::get('/', function () {
    $testimonials = \App\Models\Testimonial::with('user')->latest()->get();
    return view('welcome', compact('testimonials'));
})->name('home');

// ============================================================
// DASHBOARD REDIRECT
// ============================================================
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'mechanic' => redirect()->route('mechanic.dashboard'),
        default    => redirect()->route('customer.dashboard'),
    };
})->name('dashboard');

// ============================================================
// PROFILE ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// CHAT SYSTEM ROUTES (SHARED)
// ============================================================
// Ditempatkan di sini agar bisa diakses Customer, Admin & Mekanik
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/{id}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{id}/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/booking/{id}/chat/toggle', [ChatController::class, 'toggleStatus'])->name('chat.toggle');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'verified', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('bookings', AdminBooking::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('/bookings/{id}/assign', [AdminBooking::class, 'assignMechanic'])->name('bookings.assign');

        Route::resource('services', AdminService::class)->names('services');
        Route::resource('products', AdminProduct::class)->names('products');
        Route::patch('/products/{product}/update-stock', [AdminProduct::class, 'updateStock'])->name('products.updateStock');

        Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrder::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrder::class, 'updateStatus'])->name('orders.updateStatus');
        Route::delete('/orders/{order}', [AdminOrder::class, 'destroy'])->name('orders.destroy');

        Route::get('/penghasilan', [ReportController::class, 'penghasilan'])->name('penghasilan');

        Route::resource('mechanics', MechanicController::class)->names('mechanics');
        Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');

        // PAYMENT ADMIN
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show'); 
        Route::post('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirm'])->name('payments.confirm');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    });

// ============================================================
// CUSTOMER ROUTES
// ============================================================
Route::middleware(['auth', 'verified'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

        Route::get('/testimoni', [TestimonialController::class, 'create'])->name('testimoni.create');
        Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimoni.store');

        Route::get('/queue', [BookingQueueController::class, 'index'])->name('queue.index');
        Route::get('/queue/filter', [BookingQueueController::class, 'filter'])->name('queue.filter');

        Route::get('/products', [CustomerProduct::class, 'index'])->name('products');
        Route::get('/products/{product}', [CustomerProduct::class, 'show'])->name('products.show');
        
        // --- ROUTE ORDERS ---
        Route::get('/orders', [CustomerOrder::class, 'index'])->name('orders.index'); // Penambahan Baris Ini
        Route::get('/products/{product}/beli', [CustomerOrder::class, 'create'])->name('orders.create');
        Route::post('/products/{product}/beli', [CustomerOrder::class, 'store'])->name('orders.store');

        Route::get('/booking', [CustomerBooking::class, 'index'])->name('booking.index');
        Route::get('/booking/create', [CustomerBooking::class, 'create'])->name('booking.create');
        Route::post('/booking', [CustomerBooking::class, 'store'])->name('booking.store');

        Route::resource('/vehicles', VehicleController::class)->names('vehicles');
        Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'index'])->name('services');

        // CUSTOMER PAYMENT
        Route::get('/payment', [CustomerPaymentController::class, 'index'])->name('payment.index');
        Route::post('/payment/store', [CustomerPaymentController::class, 'store'])->name('payment.store');

        // TAMBAHKAN BARIS INI untuk menghilangkan error "Route not defined"
        Route::delete('/payment/{payment}', [CustomerPaymentController::class, 'destroy'])->name('payment.destroy');

        Route::get('/payment/booking/{booking_id}', [CustomerPaymentController::class, 'create'])->name('payment.create');
        Route::get('/payment/product/{order_id}', [CustomerPaymentController::class, 'createProduct'])->name('payment.product');
    });

// ============================================================
// MECHANIC ROUTES
// ============================================================
Route::middleware(['auth', 'verified', 'role:mechanic'])
    ->prefix('mechanic')
    ->name('mechanic.')
    ->group(function () {
        Route::get('/dashboard', [MechanicDashboard::class, 'index'])->name('dashboard');
        Route::get('/jobs', [MechanicJobController::class, 'index'])->name('jobs.index');
        Route::patch('/jobs/{id}', [MechanicJobController::class, 'update'])->name('jobs.update');
    });

require __DIR__ . '/auth.php';