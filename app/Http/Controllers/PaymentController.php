<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Discount;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;

class PaymentController extends Controller
{
    // 🧭 Trang hiển thị thông tin thanh toán
    public function index(Request $request)
    {
        $order_id = session('order_id');

        // 🧩 Lấy danh sách mã giảm giá đang hoạt động
        $discounts = Discount::where('active', true)->get();

        // 🧪 Dữ liệu mẫu để test
        if (!$order_id) {
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

            return view('auth.thanhtoan', compact('order', 'discounts'));
        }

        $order = Order::with([
            'tickets.showtime.film',
            'tickets.showtime.cinema',
            'tickets.showtime.room',
            'tickets.seat',
            'user'
        ])->findOrFail($order_id);

        return view('auth.thanhtoan', compact('order', 'discounts'));
    }

    // 💳 Xử lý thanh toán và gửi vé điện tử
    public function process(Request $request)
    {
        $order_id = session('order_id');

        if (!$order_id) {
            return redirect()->route('thanhtoan')->with('error', 'Không tìm thấy đơn hàng.');
        }

        $order = Order::with([
            'tickets.showtime.film',
            'tickets.showtime.cinema',
            'tickets.showtime.room',
            'tickets.seat',
            'user'
        ])->findOrFail($order_id);

        $order->status = 'paid';
        $order->save();

        // ✅ Tạo mã QR (nội dung có thể tùy chỉnh)
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate('Mã vé: ' . $order->id));

        // ✅ Tạo file PDF chứa thông tin vé
        $pdf = Pdf::loadView('emails.ticket', [
            'order' => $order,
            'qrCode' => $qrCode
        ]);

        // ✅ Gửi email xác nhận
        Mail::to($order->user->email)->send(new TicketMail($order, $pdf));

        // ✅ Xóa session
        session()->forget('order_id');

        return redirect()->route('payment.success')->with('success', 'Thanh toán thành công! Vé điện tử đã được gửi đến email của bạn.');
    }
}
