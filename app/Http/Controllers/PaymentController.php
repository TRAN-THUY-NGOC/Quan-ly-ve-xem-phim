<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Hiển thị trang thanh toán
    public function show()
    {
        return view('auth.thanhtoan');
    }

    // Xử lý khi người dùng xác nhận thanh toán
    public function process(Request $request)
    {
        // Tùy bạn muốn xử lý gì: lưu DB, gửi mail, API ngân hàng, v.v.
        // Tạm thời mình làm ví dụ đơn giản:
        return back()->with('success', 'Thanh toán thành công!');
    }
}
