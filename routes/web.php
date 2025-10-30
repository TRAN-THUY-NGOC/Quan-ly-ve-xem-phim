<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim 🎬
|--------------------------------------------------------------------------
*/

// 1️⃣ TRANG CHỦ
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2️⃣ DASHBOARD CHUYỂN HƯỚNG SAU ĐĂNG NHẬP
Route::get('/dashboard', function () {
    $user = Auth::user();
    $roleName = $user->role->name ?? $user->role ?? '';

    return $roleName === 'Admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

// 3️⃣ PROFILE (CHO MỌI USER ĐÃ LOGIN)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.profileUser');
});

// 4️⃣ ADMIN
Route::prefix('admin')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::get('/update-info', [AdminController::class, 'editInfo'])->name('admin.editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('admin.updateInfo');
});

// 5️⃣ KHÁCH HÀNG (CUSTOMER)
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    // Danh sách & chi tiết phim
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.movieshow');

    // Bình luận
    Route::post('/movies/{id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // Đặt vé
    Route::get('/movies/{id}/booking', [BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/movies/{id}/booking', [BookingController::class, 'store'])->name('booking.store');
});

// Trang thanh toán sau khi chọn ghế
Route::get('/bookings/{booking}/payment', [BookingController::class, 'showPaymentPage'])->name('booking.payment');
// === THANH TOÁN ===
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    Route::get('/payment/{id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{id}/complete', [App\Http\Controllers\PaymentController::class, 'complete'])->name('payment.complete');

Route::get('/user/bookings', [App\Http\Controllers\BookingController::class, 'history'])
    ->name('booking.history')
    ->middleware(['auth', 'checkRole:Customer']);

Route::get('/user/bookings', [BookingController::class, 'history'])
    ->name('booking.history')
    ->middleware(['auth', 'checkRole:Customer']);

// ✅ Trang hiển thị thanh toán
Route::get('/payment/{id}', [PaymentController::class, 'show'])
    ->name('payment.show')
    ->middleware(['auth', 'checkRole:Customer']);

// ✅ Xác nhận thanh toán (nút “Thanh toán” trong giao diện)
Route::post('/payment/{id}/complete', [PaymentController::class, 'complete'])
    ->name('payment.complete')
    ->middleware(['auth', 'checkRole:Customer']);
});



require __DIR__.'/auth.php';
