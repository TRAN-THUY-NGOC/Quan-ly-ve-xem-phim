<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketController;   // <- dùng Ticket thay Booking
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim
|--------------------------------------------------------------------------
| - Đặt vé theo showtime (showtimes/{id}) để biết rõ phòng/giờ/ghế.
| - Dùng TicketController thay BookingController cho đồng bộ với bảng tickets.
| - Tránh redirect loop bằng cách cấu hình HOME trong RouteServiceProvider.
*/

Route::get('/', fn() => view('welcome'))->name('home');

/** ---------------------- Auth required ---------------------- */
Route::middleware('auth')->group(function () {

    // Profile cho mọi user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.view');

    // Dashboard mặc định (sau đăng nhập)
    // Gợi ý: trong RouteServiceProvider::HOME trỏ tới route này cho user thường
    Route::get('/dashboard', [MovieController::class, 'index'])->name('dashboard');
});

/** ---------------------- Admin only ---------------------- */
Route::prefix('admin')
    ->middleware(['auth', 'checkRole:Admin'])
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
        Route::get('/update-info', [AdminController::class, 'editInfo'])->name('admin.editInfo');
        Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('admin.updateInfo');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    });

/** ---------------------- Customer only ---------------------- */
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {

    // Danh sách & chi tiết phim
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Bình luận theo phim (lưu ý: cần có bảng comments tương ứng)
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Đặt vé THEO SUẤT CHIẾU (chuẩn)
    Route::get('/showtimes/{showtime}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/showtimes/{showtime}/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // Lịch sử vé của user
    Route::get('/user/tickets', [TicketController::class, 'history'])->name('tickets.history');

    // Thanh toán theo ticket
    Route::get('/payments/{ticket}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{ticket}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
});

require __DIR__.'/auth.php';
