<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim (Đã tối ưu hóa)
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ MẶC ĐỊNH ---
Route::get('/', function () {
    return view('welcome');
})->name('home');


// --- 2. LOGIC ĐIỀU HƯỚNG DASHBOARD (Dùng sau khi đăng nhập thành công) ---
// Route này chỉ kiểm tra Auth và chuyển hướng user đến Dashboard phù hợp
Route::get('/dashboard', function () {
    $user = Auth::user();

    // LẤY TÊN VAI TRÒ TỪ MODEL USER
    // Giả sử mối quan hệ Role đã được định nghĩa: $user->role->name
    // HOẶC dùng cột 'role' nếu bạn lưu trực tiếp tên vai trò trong bảng users
    $roleName = $user->role->name ?? $user->role ?? ''; 
    // Dùng $user->role->name nếu bạn dùng FK, hoặc $user->VaiTro nếu bạn dùng tên cột đó

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    
    // Mọi người dùng đã đăng nhập khác (Khách hàng)
    return redirect()->route('user.dashboard');

})->middleware('auth')->name('dashboard');


// --- 3. NHÓM ROUTE CHUNG (Cần đăng nhập) ---
Route::middleware('auth')->group(function () {
    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Thêm các route KHÔNG CẦN phân quyền chi tiết tại đây (VD: Tra cứu phim)
});


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
});


// --- 6. AUTH (LOGIN / REGISTER / LOGOUT) ---
require __DIR__.'/auth.php';