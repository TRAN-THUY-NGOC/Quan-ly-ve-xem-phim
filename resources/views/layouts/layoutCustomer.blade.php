<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LDCinema')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- CSS riêng của bạn (nếu có) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    {{-- HEADER / NAVBAR --}}
    <header class="bg-light shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand fw-bold text-danger" href="{{ url('/') }}">
                <i class="fa-solid fa-film"></i> LDCINEMA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Shop quà tặng</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Mua vé</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Phim</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Rạp chiếu phim</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tin mới & Ưu đãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                </ul>

                {{-- Khu vực tài khoản --}}
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-danger" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-semibold text-danger" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Tài khoản</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark text-light text-center py-4 mt-auto">
        <div class="container">
            <h5 class="fw-bold text-danger mb-2"><i class="fa-solid fa-film"></i> LDCINEMA</h5>
            <p class="mb-1">Địa chỉ: 123 Đường ABC, Quận 1, TP.HCM</p>
            <p class="mb-1">Email: support@ldcinema.vn | Hotline: 1900 1234</p>
            <p class="mb-0">&copy; {{ date('Y') }} LDCinema. Mọi quyền được bảo lưu.</p>
        </div>
    </footer>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
