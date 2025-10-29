<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    {{-- Header --}}
    <header class="bg-dark text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="mb-0">🎥 Rạp Chiếu Phim</h4>
            <nav>
                <a href="{{ route('user.dashboard') }}" class="text-white me-3 text-decoration-none">Phim</a>
                <a href="#" class="text-white me-3 text-decoration-none">Vé của tôi</a>
                <a href="{{ route('profile.profileUser') }}" class="text-white text-decoration-none">Tài khoản</a>
            </nav>
        </div>
    </header>

    {{-- Nội dung chính --}}
    <main class="container my-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-light text-center py-3 border-top">
        <small class="text-muted">© 2025 - Rạp Chiếu Phim Online | Thiết kế bởi Luân-San 😊❤️</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
