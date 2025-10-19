<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ticket;

class BookingController extends Controller
{
    // Trang chọn vé (hiện tại chỉ mô phỏng)
    public function showBookingForm()
    {
        return view('user.datve');
    }

    // Xử lý khi người dùng xác nhận đặt vé
    public function store(Request $request)
    {
        // 👉 Sau này bạn sẽ lấy từ form
        $total = 120000; // Giả lập tổng tiền
        $user_id = auth()->id() ?? 1;

        // Tạo đơn hàng
    $order = Order::create([
        'user_id' => $user_id,
        'total_amount' => $total, // đổi tên cột đúng với database
        'status' => 'pending',
    ]);

        // (Tuỳ chọn) tạo vé mẫu
        Ticket::create([
            'order_id' => $order->id,
            'seat_number' => 'A5',
            'price' => 120000,
        ]);

        // Lưu vào session để sang trang thanh toán
        session(['order_id' => $order->id]);

        // Chuyển sang trang thanh toán
        return redirect()->route('thanhtoan');
    }
}
