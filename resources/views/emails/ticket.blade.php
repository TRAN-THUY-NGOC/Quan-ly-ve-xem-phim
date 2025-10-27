<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; }
        .ticket { border: 2px dashed #333; padding: 20px; margin: 20px; border-radius: 10px; }
    </style>
</head>
<body>
    <h1>🎬 Vé Xem Phim Điện Tử</h1>
    <div class="ticket">
        <p><b>Mã vé:</b> {{ $order->id }}</p>
        <p><b>Khách hàng:</b> {{ $order->user->name }}</p>

        @foreach ($order->tickets as $ticket)
            <p><b>Phim:</b> {{ $ticket->showtime->film->name }}</p>
            <p><b>Ghế:</b> {{ $ticket->seat->name }}</p>
            <p><b>Rạp:</b> {{ $ticket->showtime->cinema->name }} - {{ $ticket->showtime->room->name }}</p>
            <p><b>Suất:</b> {{ $ticket->showtime->date }} - {{ $ticket->showtime->time }}</p>
        @endforeach

        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
    </div>
    <p>Vui lòng quét mã QR này tại quầy vé hoặc cổng vào.</p>
</body>
</html>
