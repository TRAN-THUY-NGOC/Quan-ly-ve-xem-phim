<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FilmController;

<<<<<<< HEAD
// ==== ADMIN ====
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhimController;

// ======================================================================
// 🌍 TRANG CHỦ
// ======================================================================
=======
/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim (Đã tối ưu hóa)
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ MẶC ĐỊNH ---
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
Route::get('/', function () {
    return view('welcome');
})->name('home');

<<<<<<< HEAD
// ======================================================================
// 🎬 DASHBOARD CHÍNH (TỰ ĐỘNG PHÂN LOẠI)
// ======================================================================
=======

// --- 2. LOGIC ĐIỀU HƯỚNG DASHBOARD (Dùng sau khi đăng nhập thành công) ---
// Route này chỉ kiểm tra Auth và chuyển hướng user đến Dashboard phù hợp
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

<<<<<<< HEAD
    if (($user->role ?? $user->VaiTro ?? '') === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
=======
    // LẤY TÊN VAI TRÒ TỪ MODEL USER
    // Giả sử mối quan hệ Role đã được định nghĩa: $user->role->name
    // HOẶC dùng cột 'role' nếu bạn lưu trực tiếp tên vai trò trong bảng users
    $roleName = $user->role->name ?? $user->role ?? ''; 
    // Dùng $user->role->name nếu bạn dùng FK, hoặc $user->VaiTro nếu bạn dùng tên cột đó

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    
    // Mọi người dùng đã đăng nhập khác (Khách hàng)
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
    return redirect()->route('user.dashboard');

})->middleware('auth')->name('dashboard');

<<<<<<< HEAD
// ======================================================================
// 👤 PROFILE NGƯỜI DÙNG
// ======================================================================
=======

// --- 3. NHÓM ROUTE CHUNG (Cần đăng nhập) ---
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
Route::middleware('auth')->group(function () {
    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Thêm các route KHÔNG CẦN phân quyền chi tiết tại đây (VD: Tra cứu phim)
});

<<<<<<< HEAD
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
=======

// --- 4. NHÓM ROUTE KHÁCH HÀNG (USER/CUSTOMER) ---
// Dùng checkRole để đảm bảo chỉ Customer được truy cập
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard'); // resources/views/user/dashboard.blade.php
    })->name('user.dashboard');

    // Thêm các route chức năng Khách hàng (Đặt vé, Lịch sử, Thanh toán) tại đây
});


// --- 5. NHÓM ROUTE QUẢN TRỊ (ADMIN) ---
// Dùng checkRole để đảm bảo chỉ Admin được truy cập
Route::prefix('admin')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
    })->name('admin.dashboard');

    // Thêm các route chức năng Admin (QL Phim, QL Suất chiếu, Báo cáo) tại đây
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
});

// ======================================================================
// 🎟️ ĐẶT VÉ + THANH TOÁN
// ======================================================================
Route::get('/datve', [BookingController::class, 'showBookingForm'])->name('datve');
Route::post('/datve', [BookingController::class, 'store'])->name('datve.store');

<<<<<<< HEAD
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
=======
// --- 6. AUTH (LOGIN / REGISTER / LOGOUT) ---
require __DIR__.'/auth.php';
>>>>>>> 6354ef24df4242f8302b8473aaf54a78ccd67cf2
