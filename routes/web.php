<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim
|--------------------------------------------------------------------------
| - Đặt vé theo SUẤT CHIẾU (showtimes/{showtime}) để xác định phòng/giờ/ghế.
| - Dùng TicketController thay BookingController (khớp bảng tickets).
| - Tránh redirect loop: cấu hình HOME trong RouteServiceProvider.
| - Route model binding: {movie}, {showtime}, {ticket} (có thể dùng id nếu muốn).
*/

Route::view('/', 'welcome')->name('home');

/** ---------------------- Auth required ---------------------- */
Route::middleware('auth')->group(function () {
    // Hồ sơ người dùng
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.view');

    // Dashboard chung sau đăng nhập (gợi ý: set HOME tới route này cho Customer)
    Route::get('/dashboard', [MovieController::class, 'index'])->name('dashboard');
});

/** ---------------------- Admin only ---------------------- */
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'checkRole:Admin'])
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::get('/update-info', [AdminController::class, 'editInfo'])->name('editInfo');
        Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('updateInfo');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('updateProfile');
    });

/** ---------------------- Customer only ---------------------- */
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    // Danh sách & chi tiết phim
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Bình luận theo phim (cần bảng comments)
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:10,1') // tránh spam comment
        ->name('comments.store');

    // Đặt vé THEO SUẤT CHIẾU
    Route::get('/showtimes/{showtime}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/showtimes/{showtime}/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // Lịch sử vé của user
    Route::get('/user/tickets', [TicketController::class, 'history'])->name('tickets.history');

    // Thanh toán theo ticket
    Route::get('/payments/{ticket}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{ticket}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
});

require __DIR__.'/auth.php';
