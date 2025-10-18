<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes – Quản lý vé xem phim 🎬
|--------------------------------------------------------------------------
| Chỉ giữ những gì đang có: Dashboard Admin/User, Profile, Auth
| Không thêm route chức năng chưa tồn tại.
|--------------------------------------------------------------------------
*/

// --- TRANG CHỦ ---
Route::get('/', function () {
    return view('welcome');
});

// --- DASHBOARD CHÍNH: tự điều hướng theo vai trò ---
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // ✅ Điều hướng theo vai trò
    if (($user->role ?? $user->VaiTro ?? '') === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// --- PROFILE NGƯỜI DÙNG ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- USER (KHÁCH HÀNG) ---
Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard'); // resources/views/user/dashboard.blade.php
    })->name('user.dashboard');
});

// --- ADMIN (QUẢN TRỊ) ---
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
    })->name('admin.dashboard');
});

// USER ROUTE
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

// ADMIN ROUTE
Route::prefix('admin')->middleware(['auth', 'checkRole:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


// Hiển thị trang thanh toán
Route::get('/thanhtoan', [PaymentController::class, 'index'])->name('thanhtoan');

// Xử lý form thanh toán (nếu có)
Route::post('/thanhtoan', [PaymentController::class, 'process'])->name('thanhtoan.process');

// --- AUTH (LOGIN / REGISTER / LOGOUT) ---
require __DIR__.'/auth.php';

