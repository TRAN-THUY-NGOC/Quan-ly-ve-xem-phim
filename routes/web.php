<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FilmController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\RoomController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim
|--------------------------------------------------------------------------
*/

// --- 1. TRANG CHỦ ---
Route::get('/', function () {
    return view('welcome');
})->name('home');


// --- 2. DASHBOARD (TỰ ĐỘNG CHUYỂN HƯỚNG SAU KHI LOGIN) ---
Route::get('/dashboard', function () {
    $user = Auth::user();
    $roleName = $user->role->name ?? $user->role ?? ''; // tuỳ vào bạn lưu vai trò kiểu nào

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');


// --- 3. PROFILE (DÙNG CHUNG) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- 4. ROUTE KHÁCH HÀNG ---
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});


// --- 5. ROUTE QUẢN TRỊ VIÊN ---
Route::prefix('admin')->middleware(['auth', 'checkRole:Admin'])->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Cập nhật thông tin admin
    Route::get('/update-info', [AdminController::class, 'editInfo'])->name('editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('updateInfo');

    // =====================
    // QUẢN LÝ PHIM (CRUD)
    // =====================
    Route::resource('films', FilmController::class);

    // ===========================
    // QUẢN LÝ SUẤT CHIẾU (CRUD)
    // ===========================
    Route::resource('showtimes', ShowtimeController::class);

    // ===========================
    // QUẢN LÝ PHÒNG CHIẾU (CRUD)
    // ===========================
    Route::resource('rooms', RoomController::class);

    // Thống kê số lượng ghế theo loại ghế trong phòng chiếu
    Route::get('/admin/rooms/statistics', [RoomController::class, 'seatStatistics'])->name('admin.rooms.statistics');


});


// --- 6. AUTH (LOGIN / REGISTER / LOGOUT) ---
require __DIR__ . '/auth.php';
