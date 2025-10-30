<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Booking;

class PaymentController extends Controller
{
    // 🧾 Hiển thị trang thanh toán
    public function show($paymentId)
    {
        $payment = Payment::with('booking.movie', 'booking.seat')->findOrFail($paymentId);
        return view('payments.payment', compact('payment'));
    }

    // 💰 Xử lý xác nhận thanh toán
    public function complete(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->update([
            'status' => 'completed',
            'payment_method' => $request->payment_method ?? 'COD',
            'transaction_code' => 'TX-' . strtoupper(uniqid()),
            'paid_at' => Carbon::now()
        ]);

        return redirect()->route('user.dashboard')->with('success', '🎉 Thanh toán thành công!');
    }
    
}
