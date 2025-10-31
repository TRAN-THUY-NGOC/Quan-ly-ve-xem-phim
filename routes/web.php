<?php

use Illuminate\Support\Facades\Route;

// Controllers (app level)
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

// Admin namespace
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MoviesController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\ShowtimesController;
use App\Http\Controllers\Admin\PricesController;
use App\Http\Controllers\Admin\TicketsController as AdminTicketsController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim
|--------------------------------------------------------------------------
| - Đặt vé theo SUẤT CHIẾU (showtimes/{showtime}).
| - TicketController dùng cho khách; AdminTicketsController cho admin.
| - Route model binding: {movie}, {showtime}, {ticket}.
*/

// ---------------------- Public ----------------------
Route::view('/', 'welcome')->name('home');

// ---------------------- Auth required (mọi user) ----------------------
Route::middleware('auth')->group(function () {
    // Hồ sơ người dùng
    Route::get('/profile',        [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',      [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',     [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view',   [ProfileController::class, 'profileUser'])->name('profile.view');

    // Dashboard chung sau đăng nhập
    Route::get('/dashboard', [MovieController::class, 'index'])->name('dashboard');
});

// ---------------------- Admin only ----------------------
Route::prefix('admin')->as('admin.')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    // Dashboard + cấu hình thông tin
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::get('/update-info',  [AdminController::class, 'editInfo'])->name('editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('updateInfo');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('updateProfile');

    // Quản lý phim
    Route::resource('movies', MoviesController::class)->except(['show']);

    // Quản lý phòng chiếu
    Route::resource('rooms', RoomsController::class)->except(['show']);
    Route::post('rooms/{room}/generate-seats', [RoomsController::class, 'generateSeats'])->name('rooms.generateSeats');

    // Quản lý suất chiếu
    Route::resource('showtimes', ShowtimesController::class)->except(['show']);

    // Quản lý giá vé theo suất & loại ghế
    Route::get('prices',  [PricesController::class, 'index'])->name('prices.index');
    Route::post('prices', [PricesController::class, 'store'])->name('prices.store');

    // Quản lý đơn vé
    Route::get('tickets', [AdminTicketsController::class, 'index'])->name('tickets.index');
    Route::post('tickets/{ticket}/cancel', [AdminTicketsController::class, 'cancel'])->name('tickets.cancel');
    Route::post('tickets/{ticket}/refund', [AdminTicketsController::class, 'refund'])->name('tickets.refund');

    // Báo cáo & thống kê
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');

    // >>> Quản lý tài khoản (đặt đúng NHÓM ADMIN)
    Route::resource('users', UsersController::class)->only([
        'index','create','store','edit','update','destroy']);

    Route::post('users/{user}/reset-password',
        [UsersController::class, 'resetPassword']
    )->middleware('throttle:5,1')->name('users.resetPassword');

});

// ---------------------- Customer only ----------------------
Route::middleware(['auth', 'checkRole:Customer'])->group(function () {
    // Danh sách & chi tiết phim
    Route::get('/user/dashboard', [MovieController::class, 'index'])->name('user.dashboard');
    Route::get('/movies',         [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Bình luận theo phim
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('comments.store');

    // Đặt vé THEO SUẤT CHIẾU
    Route::get('/showtimes/{showtime}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/showtimes/{showtime}/tickets',       [TicketController::class, 'store'])->name('tickets.store');

    // Lịch sử vé của user
    Route::get('/user/tickets', [TicketController::class, 'history'])->name('tickets.history');

    // Thanh toán theo ticket
    Route::get('/payments/{ticket}',           [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{ticket}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
});

// ---------------------- Auth scaffolding ----------------------
require __DIR__.'/auth.php';
