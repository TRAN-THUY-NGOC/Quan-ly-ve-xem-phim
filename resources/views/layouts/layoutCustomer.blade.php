{{-- resources/views/layouts/layoutCustomer.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LOTTE CINEMA')</title> 
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
</head>

<body>
    {{-- ================================================================= --}}
    {{--                                HEADER                               --}}
    {{-- ================================================================= --}}
    <header class="shadow-sm">
        {{-- TOP BAR --}}
        <div class="top-bar border-bottom">
            <div class="container d-flex justify-content-between align-items-center py-1 px-3">
                
                <a href="https://facebook.com/lottecinema" target="_blank" class="text-dark text-decoration-none d-flex align-items-center fw-bold small">
                    <img src="{{ asset('assets/images/facebook-icon.png') }}" alt="Facebook Logo" class="me-1" style="height: 16px;">
                    Lotte Cinema Facebook
                </a>

                <div class="d-flex align-items-center small">
                    @auth
                        @if (Auth::user()->role === 'Admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-dark text-decoration-none me-3 fw-bold">ADMIN</a>
                            <span class="text-dark me-3">|</span>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="text-dark text-decoration-none me-3">Hồ sơ</a>
                        <span class="text-dark me-3">|</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline p-0 m-0">
                            @csrf
                            <button class="btn btn-link p-0 text-dark text-decoration-none small" type="submit">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                         <a href="{{ route('login') }}" class="text-dark text-decoration-none me-3">Đăng nhập</a>
                         <span class="text-dark me-3">|</span>
                         <a href="{{ route('register') }}" class="text-dark text-decoration-none me-3">Đăng ký</a>
                    @endauth

                    <span class="text-dark me-3">|</span>
                    <a href="#" class="text-dark text-decoration-none me-3">Thẻ thành viên</a>
                    <a href="#" class="text-dark text-decoration-none me-3">Hỗ trợ khách hàng</a>
                    
                    <button class="btn btn-sm btn-dark ms-3">English</button>
                </div>
            </div>
        </div>

        {{-- LOGO VÀ TÊN CINEMA --}}
        <div class="logo-bar text-center py-3">
            <div class="container d-flex justify-content-center align-items-center">
                <a href="/">
                    <img src="{{ asset('assets/images/cinema-logo.png') }}" alt="CINEMA Logo" style="height: 70px;">
                </a>
            </div>
        </div>
    </header>

    {{-- Thanh Tiêu đề Trang --}}
    <div class="page-title-bar py-3 border-top border-bottom">
        <div class="container d-flex align-items-center">
            {{-- Icon Menu --}}
            <button class="btn menu-toggle-btn me-3 p-2 border-0">
                <i class="bi bi-list fs-3"></i>
            </button>
            
            {{-- Tiêu đề Nội dung Trang --}}
            <div class="text-center w-100">
                 <h4 class="mb-0 fw-bold d-inline-block">@yield('page-title', 'QUẢN LÝ KHÁCH HÀNG')</h4>
                 <div class="underline-title mx-auto mt-1"></div>
            </div>
        </div>
    </div>
    
    {{-- ================================================================= --}}
    {{--                             NỘI DUNG CHÍNH                            --}}
    {{-- ================================================================= --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- ================================================================= --}}
    {{--                                FOOTER                               --}}
    {{-- ================================================================= --}}
    <footer class="text-center pt-4 mt-5 border-top">
        <div class="container">
            {{-- LOGO --}}
            <div class="logo-footer d-flex justify-content-center align-items-center mb-3">
                <img src="{{ asset('assets/images/cinema-logo-light.png') }}" alt="CINEMA Logo Light" style="height: 35px;">
            </div>

            {{-- FOOTER LINKS --}}
            <div class="footer-links text-secondary mb-3 small">
                <a href="#" class="text-dark text-decoration-none mx-2">Chính Sách Khách Hàng Thường Xuyên</a> |
                <a href="#" class="text-dark text-decoration-none mx-2">Chính Sách Bảo Mật Thông Tin</a> |
                <a href="#" class="text-dark text-decoration-none mx-2">Điều Khoản Sử Dụng</a>
            </div>

            {{-- THÔNG TIN CÔNG TY --}}
            <div class="footer-company text-muted small pb-4" style="line-height: 1.5;">
                <b>CÔNG TY TNHH LOTTE CINEMA VIỆT NAM</b><br>
                Giấy CNĐKKD: 0302575928, đăng ký lần đầu ngày 02/05/2008, đăng ký thay đổi lần thứ 10 ngày 30/03/2018, cấp bởi Sở KHĐT Thành phố Hồ Chí Minh<br>
                Địa chỉ: Tầng 3, TTTM Lotte, số 469 đường Nguyễn Hữu Thọ, Phường Tân Hưng, Quận 7, TP.HCM, Việt Nam<br>
                Hotline: (028) 3775 2524<br>
                COPYRIGHT © LOTTECINEMA/VN.COM - ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>