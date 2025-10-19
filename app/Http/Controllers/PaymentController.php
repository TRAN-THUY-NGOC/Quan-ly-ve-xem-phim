<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;

class PaymentController extends Controller
{
    // 🧭 Trang hiển thị thông tin thanh toán
    public function index(Request $request)
    {
        $order_id = session('order_id');

        // 🧩 Lấy danh sách mã giảm giá đang hoạt động
        $discounts = Discount::where('active', true)->get();

        // 🧪 Nếu chưa có đơn hàng (chưa tích hợp đặt vé), tạo dữ liệu mẫu để test giao diện
        if (!$order_id) {
            // Tạo object giả lập $order để không bị lỗi
            $order = (object) [
                'id' => 999,
                'total' => 180000,
                'user' => (object) [
                    'name' => 'Nguyễn Văn A',
                    'phone' => '0901234567',
                    'email' => 'nguyenvana@example.com'
                ],
                'tickets' => collect([
                    (object)[
                        'price' => 90000,
                        'seat' => (object)['name' => 'A1'],
                        'showtime' => (object)[
                            'date' => '2025-10-20',
                            'time' => '19:30',
                            'film' => (object)['name' => 'Avengers: Secret Wars'],
                            'cinema' => (object)['name' => 'CGV Nguyễn Trãi'],
                            'room' => (object)['name' => 'Phòng 3D']
                        ]
                    ],
                    (object)[
                        'price' => 90000,
                        'seat' => (object)['name' => 'A2'],
                        'showtime' => (object)[
                            'date' => '2025-10-20',
                            'time' => '19:30',
                            'film' => (object)['name' => 'Avengers: Secret Wars'],
                            'cinema' => (object)['name' => 'CGV Nguyễn Trãi'],
                            'room' => (object)['name' => 'Phòng 3D']
                        ]
                    ]
                ])
            ];

            // Truyền cả $discounts vào view để hiển thị
            return view('auth.thanhtoan', compact('order', 'discounts'));
        }

        // 🧾 Nếu có order thật (sau này nhóm bạn tích hợp), giữ nguyên logic cũ
        $order = Order::with([
            'tickets.showtime.film',
            'tickets.showtime.cinema',
            'tickets.showtime.room',
            'tickets.seat',
            'user'
        ])->findOrFail($order_id);

        return view('auth.thanhtoan', compact('order', 'discounts'));
    }

    // 💳 Hàm xử lý khi người dùng xác nhận thanh toán
    public function process(Request $request)
    {
        // Lấy order_id từ session
        $order_id = session('order_id');

        if (!$order_id) {
            return redirect()->route('thanhtoan')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Cập nhật trạng thái đơn hàng
        $order = Order::findOrFail($order_id);
        $order->status = 'paid'; // Hoặc 'completed' nếu bạn đặt tên vậy trong database
        $order->save();

        // ✅ XÓA session sau khi thanh toán xong
        session()->forget('order_id');

        // Chuyển sang trang cảm ơn
        return redirect()->route('payment.success')->with('success', 'Thanh toán thành công!');
    }
}
