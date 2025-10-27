<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Xin chào {{ $order->user->name }},</h2>

    <p>Bạn đã thanh toán thành công đơn hàng #{{ $order->id }}.</p>
    <p>Thông tin vé của bạn:</p>

    <ul>
        @foreach ($order->tickets as $ticket)
            <li>
                <b>Phim:</b> {{ $ticket->showtime->film->name }} <br>
                <b>Rạp:</b> {{ $ticket->showtime->cinema->name }} <br>
                <b>Phòng:</b> {{ $ticket->showtime->room->name }} <br>
                <b>Ghế:</b> {{ $ticket->seat->name }} <br>
                <b>Thời gian:</b> {{ $ticket->showtime->date }} - {{ $ticket->showtime->time }}
            </li>
        @endforeach
    </ul>

    <p><b>Tổng tiền:</b> {{ number_format($order->total) }} VND</p>
    <p>🎟 Vé điện tử (PDF) đã được đính kèm trong email này.</p>
    <p>Cảm ơn bạn đã sử dụng hệ thống đặt vé của chúng tôi!</p>

    <hr>
    <small>Hệ thống đặt vé CINEMA — {{ date('Y') }}</small>
</body>
</html>
