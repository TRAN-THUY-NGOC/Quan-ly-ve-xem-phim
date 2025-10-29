<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim (Đã tối ưu hóa)
|--------------------------------------------------------------------------
*/

// --- 1️⃣ TRANG CHỦ ---
Route::get('/', function () {
    return view('welcome');
})->name('home');


// --- 2️⃣ DASHBOARD CHUYỂN HƯỚNG ---
Route::get('/dashboard', function () {
    $user = Auth::user();
    $roleName = $user->role->name ?? $user->role ?? '';

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');


// --- 3️⃣ PROFILE (DÙNG CHO MỌI USER ĐÃ LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.profileUser');
});


// --- 4️⃣ ADMIN ---
Route::prefix('admin')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::get('/update-info', [AdminController::class, 'editInfo'])->name('admin.editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('admin.updateInfo');
});


// --- 5️⃣ KHÁCH HÀNG (CUSTOMER) ---
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    // Dashboard người dùng
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');

    // Danh sách phim
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

    // Chi tiết phim
    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.movieshow');

    // Bình luận (POST)
    Route::post('/movies/{id}/comment', [CommentController::class, 'store'])->name('comments.store');
});

require __DIR__.'/auth.php';
