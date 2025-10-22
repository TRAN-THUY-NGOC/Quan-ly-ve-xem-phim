<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FilmController;

// ==== ADMIN ====
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhimController;

// ======================================================================
// 🌍 TRANG CHỦ
// ======================================================================
Route::get('/', function () {
    return view('welcome');
});

// ======================================================================
// 🎬 DASHBOARD CHÍNH (TỰ ĐỘNG PHÂN LOẠI)
// ======================================================================
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    if (($user->role ?? $user->VaiTro ?? '') === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// ======================================================================
// 👤 PROFILE NGƯỜI DÙNG
// ======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================================================================
// 👥 USER (KHÁCH HÀNG)
// ======================================================================
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

// ======================================================================
// 🛠️ ADMIN (QUẢN TRỊ)
// ======================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // === LOGIN ===
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // === DASHBOARD ===
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // === QUẢN LÝ PHIM ===
        Route::resource('/phim', PhimController::class);
    });
});

// ======================================================================
// 🎟️ ĐẶT VÉ + THANH TOÁN
// ======================================================================
Route::get('/datve', [BookingController::class, 'showBookingForm'])->name('datve');
Route::post('/datve', [BookingController::class, 'store'])->name('datve.store');

Route::get('/thanhtoan', [PaymentController::class, 'index'])->name('thanhtoan');
Route::post('/thanhtoan/process', [PaymentController::class, 'process'])->name('thanhtoan.process');
Route::get('/thanhtoan/thanhcong', function () {
    return view('auth.thanhtoan_thanhcong');
})->name('payment.success');

// ======================================================================
// 🎫 VÉ
// ======================================================================
Route::get('/ticket/{orderId}', [App\Http\Controllers\TicketController::class, 'generateTicket']);

// ======================================================================
// 🔐 AUTH (USER LOGIN/REGISTER)
// ======================================================================
require __DIR__.'/auth.php';
