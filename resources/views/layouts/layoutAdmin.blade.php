<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CINEMA')</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* --- HEADER --- */
        header {
            background-color: #fdf9f3;
            border-bottom: 1px solid #ddd;
        }

        .top-bar {
            background-color: #f7f1e7;
            padding: 4px 15px;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Căn trái cho menu và logo Facebook */
        .top-bar .left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .menu-btn {
            background-color: #bfa476;
            color: white;
            border: none;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .menu-btn:hover {
            background-color: #a78955;
        }

        /* Link Facebook */
        .fb-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .fb-link img {
            height: 18px;
            width: 18px;
            object-fit: contain;
            margin-right: 6px;
        }

        /* --- SIDEBAR --- */
        .side-menu {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #bfa476;
            padding-top: 70px;
            color: #000;
            z-index: 1000;
            transition: left 0.4s ease;
        }

        .side-menu.active {
            left: 0;
        }

        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .side-menu ul li {
            padding: 15px 25px;
            font-weight: bold;
            cursor: pointer;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .side-menu ul li:hover {
            background-color: #a78955;
            color: #fff;
        }

        /* --- LỚP PHỦ --- */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* --- LOGO & MAIN --- */
        .logo-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
        }

        .logo-bar img {
            height: 45px;
            margin-right: 10px;
        }

        .logo-bar h1 {
            color: #d82323;
            font-size: 28px;
            margin: 0;
        }

        main {
            padding: 25px;
            min-height: 70vh;
        }

        /* --- FOOTER --- */
        footer {
            background-color: #fff;
            border-top: 1px solid #ddd;
            padding: 25px 10px;
            text-align: center;
            font-size: 13px;
            color: #000;
        }

        footer .logo-footer img {
            height: 35px;
            margin-bottom: 10px;
        }

        footer h3 {
            color: #d82323;
            margin: 0;
            font-size: 22px;
        }

        footer a {
            color: #000;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="side-menu" id="sideMenu">
        <ul>
            <li><a href="{{ route('admin.films.index') }}" style="color:black;text-decoration:none;">QUẢN LÝ PHIM</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ SUẤT CHIẾU</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ PHÒNG CHIẾU</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ GIÁ VÉ</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ ĐƠN VÉ</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">BÁO CÁO & THỐNG KÊ</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ NHÂN VIÊN</a></li>
            <li><a href="#" style="color:black;text-decoration:none;">QUẢN LÝ KHÁCH HÀNG</a></li>
        </ul>
    </div>

    <div class="overlay" id="overlay"></div>

    <!-- HEADER -->
    <header>
        <div class="top-bar">
            <div class="left">
                <button class="menu-btn" id="menuBtn">
                    <i class="fa fa-bars"></i>
                </button>

                <a href="https://facebook.com" target="_blank" class="fb-link">
                    <img src="{{ asset('assets/images/Facebook.png') }}" alt="Facebook">
                    Facebook
                </a>
            </div>

            <div style="text-align:right;">
                @auth
                    <strong>{{ Auth::user()->name }}</strong> |
                    <a href="{{ route('admin.dashboard') }}">Hồ sơ</a> |

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#007bff;cursor:pointer;">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Đăng nhập</a> |
                    <a href="{{ route('register') }}">Đăng ký</a>
                @endauth
                | <a href="#">English</a>
            </div>
        </div>

        <div class="logo-bar">
            <img src="{{ asset('assets/images/logo.png') }}" alt="CINEMA">
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="logo-footer">
            <img src="{{ asset('assets/images/logo.png') }}" alt="CINEMA">
        </div>

        <div>
            Chính Sách Khách Hàng Thường Xuyên | Chính Sách Bảo Mật Thông Tin | Điều Khoản Sử Dụng
        </div>

        <div style="margin-top:15px;line-height:1.6;">
            <b>CÔNG TY TNHH CINEMA VIỆT NAM</b><br>
            Giấy CNĐKKD: 0303675393, cấp ngày 20/05/2008, sửa đổi ngày 10/03/2018,<br>
            cấp bởi Sở KHĐT TP Hồ Chí Minh<br>
            Địa chỉ: Tầng 3, TT TM CINEMA, 69 Nguyễn Hữu Thọ, Quận 7, TP.HCM<br>
            Điện thoại: (028) 3775 2524<br>
            COPYRIGHT © CINEMA.COM - ALL RIGHTS RESERVED.
        </div>
    </footer>

    <script>
        const menuBtn = document.getElementById("menuBtn");
        const sideMenu = document.getElementById("sideMenu");
        const overlay = document.getElementById("overlay");

        menuBtn.addEventListener("click", () => {
            sideMenu.classList.toggle("active");
            overlay.classList.toggle("active");
        });

        overlay.addEventListener("click", () => {
            sideMenu.classList.remove("active");
            overlay.classList.remove("active");
        });
    </script>
</body>
</html>
