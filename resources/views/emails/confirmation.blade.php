<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Xin chào {{ $order->user->name }},</h2>
    <p>Bạn đã đặt vé thành công cho bộ phim <strong>{{ $order->tickets[0]->showtime->film->name }}</strong>.</p>
    <p>Chi tiết vé của bạn được đính kèm trong file PDF (có mã QR để quét tại rạp).</p>
    <p>Cảm ơn bạn đã đặt vé tại <strong>Cinema</strong>! 🎥</p>
</body>
</html>
