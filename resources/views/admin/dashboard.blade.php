<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            font-family: Arial;
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 220px;
            background: #222;
            color: #fff;
            height: 100vh;
            padding: 15px;
            position: fixed;
            transition: 0.3s;
        }
        .sidebar.hidden { width: 0; padding: 0; overflow: hidden; }
        .sidebar h3 { margin-bottom: 20px; }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #555;
        }
        .content {
            flex: 1;
            margin-left: 220px;
            padding: 20px;
            transition: 0.3s;
        }
        .content.full { margin-left: 0; }
        .toggle-btn {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h3>🎬 Quản trị phim</h3>
        <a href="#">📽 Quản lý phim</a>
        <a href="#">🎟 Quản lý vé</a>
        <a href="#">👥 Người dùng</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="toggle-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="content" id="content">
        <button class="toggle-btn" onclick="toggleMenu()">☰ Menu</button>
        <h2>Chào, {{ $admin->name }}</h2>
        <p>Chào mừng bạn đến trang quản trị hệ thống!</p>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('hidden');
            document.getElementById('content').classList.toggle('full');
        }
    </script>
</body>
</html>
