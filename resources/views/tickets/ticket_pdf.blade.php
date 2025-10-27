<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vé điện tử</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; }
        .ticket { border: 2px solid #000; padding: 20px; border-radius: 10px; display: inline-block; }
        img { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="ticket">
        <h2>CINEMA - Vé điện tử</h2>
        <p><strong>Mã vé:</strong> {{ $order->id }}</p>
        <p><strong>Phim:</strong> {{ $order->movie_title }}</p>
        <p><strong>Suất chiếu:</strong> {{ $order->show_time }}</p>
        <p><strong>Ghế:</strong> {{ $order->seat }}</p>
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
    </div>
</body>
</html>
