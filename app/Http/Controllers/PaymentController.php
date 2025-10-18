<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Lấy order_id từ query string: /thanhtoan?order_id=1
        $order_id = $request->query('order_id');

        if (!$order_id) {
            abort(404, 'Order ID không được cung cấp.');
        }

        $order = Order::with([
            'tickets.showtime.film',
            'tickets.showtime.cinema',
            'tickets.showtime.room',
            'tickets.seat',
            'user'
        ])->findOrFail($order_id);

        return view('auth.thanhtoan', compact('order'));
    }

    public function process(Request $request)
    {
        // Xử lý thanh toán ở đây
    }
}
