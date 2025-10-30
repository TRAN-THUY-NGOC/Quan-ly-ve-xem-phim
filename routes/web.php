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
| Web Routes – QL Vé Xem Phim 🎬 (Đã tối ưu hóa lỗi Chuyển hướng)
|--------------------------------------------------------------------------
| Lỗi ERR_TOO_MANY_REDIRECTS đã được xử lý bằng cách loại bỏ Route /dashboard
| chuyển hướng qua lại. Logic phân quyền Admin/Customer nên được xử lý trong
| RedirectIfAuthenticated middleware hoặc logic trong RouteServiceProvider::HOME.
*/

// 1️⃣ TRANG CHỦ CÔNG KHAI
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2️⃣ CÁC ROUTE ĐƯỢC BẢO VỆ (Auth Group)
// Group này dành cho TẤT CẢ người dùng đã đăng nhập (Admin và Customer)
Route::middleware('auth')->group(function () {
    
    // 2.1. PROFILE (CHO MỌI USER ĐÃ LOGIN)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.profileUser');

    // 2.2. ROUTE GỐC SAU KHI LOGIN (Dashboard cũ, nay là User Dashboard)
    // Tên route này (dashboard) nên được giữ nguyên vì nó là điểm đích mặc định của Auth Scaffolding
    // Tuy nhiên, logic chuyển hướng sẽ được xử lý ở RouteServiceProvider.php
    Route::get('/dashboard', [MovieController::class, 'index'])->name('dashboard');
});

// 3️⃣ ADMIN GROUP
// Sử dụng checkRole để đảm bảo chỉ Admin mới vào được group này
Route::prefix('admin')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::get('/update-info', [AdminController::class, 'editInfo'])->name('admin.editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('admin.updateInfo');
});

// 4️⃣ KHÁCH HÀNG (CUSTOMER) GROUP
// Sử dụng checkRole để đảm bảo chỉ Customer mới vào được group này
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    
    // Danh sách & chi tiết phim (Đây là trang đích chính của Customer sau login)
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.movieshow');

    // Bình luận
    Route::post('/movies/{id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // Đặt vé
    Route::get('/movies/{id}/booking', [BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/movies/{id}/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Lịch sử đặt vé
    Route::get('/user/bookings', [BookingController::class, 'history'])->name('booking.history');

    // Thanh toán (đã gom lại)
    Route::get('/bookings/{booking}/payment', [BookingController::class, 'showPaymentPage'])->name('booking.payment'); // Hiển thị trang thanh toán sau khi chọn ghế
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{id}/complete', [PaymentController::class, 'complete'])->name('payment.complete');
});

require __DIR__.'/auth.php';