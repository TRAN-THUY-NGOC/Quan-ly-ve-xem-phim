<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QL_Cinema 🎬</title>
    <style>
        body {
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 10px rgba(255,255,255,0.2);
        }
        p {
            font-size: 1.2rem;
            color: #e0e0e0;
        }
        a {
            margin-top: 20px;
            background: #ff4081;
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 10px;
            transition: 0.3s;
        }
        a:hover {
            background: #ff79b0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <h1>🎬 Chào mừng đến với hệ thống đặt vé QL_Cinema 🎬</h1>
    <p>Laravel Framework 11.x — Dự án quản lý rạp phim của Luân-San 😎</p>
    <a href="{{ route('login') }}">Đăng nhập ngay</a>
</body>
</html>
