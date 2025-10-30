<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 🧾 Hiển thị form đặt vé
    public function showBookingForm($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $showtimes = Showtime::where('movie_id', $movieId)->get();
        $seats = Seat::all();

        return view('bookings.bookingshow', compact('movie', 'showtimes', 'seats'));
    }

    // 💾 Lưu đặt vé & tạo thanh toán
    public function store(Request $request, $movieId)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id'
        ]);

        $userId = auth()->id();
        $total = 0;
        $bookings = [];

        // ✅ Tạo vé
        foreach ($request->seat_ids as $seatId) {
            $booking = Booking::create([
                'movie_id' => $movieId,
                'showtime_id' => $request->showtime_id,
                'seat_id' => $seatId,
                'user_id' => $userId,
                'status' => 'booked',
                'payment_status' => 'pending',
                'total_price' => 75000
            ]);

            $bookings[] = $booking;
            $total += 75000; // 💰 Mỗi ghế 75k
        }

        // ✅ Tạo bản ghi thanh toán
        $payment = Payment::create([
            'ticket_id' => $bookings[0]->id, // hoặc end($bookings)
            'amount' => $total,
            'payment_method' => 'COD',
            'status' => 'pending',
            'created_at' => Carbon::now(),
        ]);

        // ➡️ Chuyển sang trang thanh toán
        return redirect()->route('payment.show', $payment->id);
    }

    // 💳 Trang thanh toán
    public function showPaymentPage($paymentId)
    {
        $payment = Payment::with(['booking.movie', 'booking.seat'])->findOrFail($paymentId);
        return view('payments.payment', compact('payment'));
    }

    // 📜 Lịch sử đặt vé
    public function history()
    {
        $bookings = Booking::with(['movie', 'showtime', 'seat', 'payment'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.history', compact('bookings'));
    }
}
