<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// App controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;

// Admin controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MoviesController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\ShowtimesController;
use App\Http\Controllers\Admin\PricesController;
use App\Http\Controllers\Admin\TicketsController as AdminTicketsController;
use App\Http\Controllers\Admin\ReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes – QL Vé Xem Phim
|--------------------------------------------------------------------------
| - Khách đặt vé theo SUẤT CHIẾU (showtimes/{showtime}).
| - TicketController cho khách; AdminTicketsController cho admin.
| - Dùng route model binding: {movie}, {showtime}, {payment}, {ticket}.
*/

// ---------------------- Public ----------------------
Route::get('/', function () {
    $u = Auth::user();
    if (!$u) return redirect()->route('login');
    return ($u->role_id == 1)
        ? redirect()->route('admin.movies.index')   // Admin -> trang quản trị
        : redirect()->route('customer.home');       // Customer -> trang chủ khách
});

// ---------------------- Auth (mọi user) ----------------------
Route::middleware('auth')->group(function () {
    // Hồ sơ
    Route::get('/profile',      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',    [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',   [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/view', [ProfileController::class, 'profileUser'])->name('profile.view');

    // Dashboard chung
    Route::get('/dashboard', [MovieController::class, 'index'])->name('dashboard');
});

// ---------------------- Admin only ----------------------
Route::prefix('admin')->as('admin.')->middleware(['auth','checkRole:Admin'])->group(function () {
    // Dashboard + cấu hình
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::get('/update-info',  [AdminController::class, 'editInfo'])->name('editInfo');
    Route::post('/update-info', [AdminController::class, 'updateInfo'])->name('updateInfo');
    Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('updateProfile');

    // Quản lý phim/phòng/suất
    Route::resource('movies', MoviesController::class)->except(['show']);
    Route::resource('rooms',  RoomsController::class)->except(['show']);
    Route::post('rooms/{room}/generate-seats', [RoomsController::class, 'generateSeats'])->name('rooms.generateSeats');
    Route::post('rooms/{room}/trim-seats',     [RoomsController::class, 'trimSeats'])->name('rooms.trimSeats');

    Route::resource('showtimes', ShowtimesController::class)->except(['show']);

    // Giá vé theo suất & loại ghế
    Route::get('prices',  [PricesController::class, 'index'])->name('prices.index');
    Route::post('prices', [PricesController::class, 'store'])->name('prices.store');
    Route::post('prices/bootstrap', [PricesController::class, 'bootstrap'])->name('prices.bootstrap');

    // Tickets admin
    Route::get('tickets',                 [AdminTicketsController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}/edit',   [AdminTicketsController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/{ticket}',        [AdminTicketsController::class, 'update'])->name('tickets.update');
    Route::post('tickets/{ticket}/cancel',[AdminTicketsController::class, 'cancel'])->name('tickets.cancel');
    Route::post('tickets/{ticket}/refund',[AdminTicketsController::class, 'refund'])->name('tickets.refund');

    // Báo cáo
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');

    // Quản lý tài khoản
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class)
        ->only(['index','create','store','edit','update','destroy']);
    Route::post('users/{user}/reset-password',
        [\App\Http\Controllers\Admin\UsersController::class, 'resetPassword']
    )->middleware('throttle:5,1')->name('users.resetPassword');
});

// ---------------------- Customer only ----------------------
Route::middleware(['auth','checkRole:Customer'])->group(function () {
    // Trang chủ khách + phim
    Route::get('/home',           [\App\Http\Controllers\Customer\HomeController::class, 'index'])->name('customer.home');
    Route::get('/movies',         [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

    // Bình luận phim
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('comments.store');

    // Đặt vé THEO SUẤT CHIẾU
    Route::get('/showtimes/{showtime}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/showtimes/{showtime}/tickets',       [TicketController::class, 'store'])->name('tickets.store');

    // Lịch sử vé của user
    Route::get('/user/tickets', [TicketController::class, 'history'])->name('tickets.history');

    // ====== PAYMENTS: chỉ dùng id bảng payments, KHÔNG tạo route /payments/{ticket} để tránh trùng ======
    Route::get('/payments/{payment}',            [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/confirm',   [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{payment}/cancel',    [PaymentController::class, 'cancel'])->name('payments.cancel');
    // (Nếu cần thao tác khác: thêm ở đây, nhưng giữ nguyên /payments/{payment} để không trùng)
});

// ---------------------- Auth scaffolding ----------------------
require __DIR__.'/auth.php';
