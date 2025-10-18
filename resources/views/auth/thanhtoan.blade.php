@extends('layouts.guest')
@section('title', 'Thanh toán')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán | CINEMA</title>
    <style>
        /* Reset & font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f7f6f3;
        }

        /* Header trên cùng */
        .top-bar {
            background-color: #f5f1e8;
            padding: 5px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .top-bar a {
            text-decoration: none;
            color: #000;
            margin: 0 8px;
        }

        /* Logo + menu chính */
        header {
            background-color: #fff;
            text-align: center;
            padding: 15px 0;
            border-bottom: 2px solid #ddd;
        }

        header img {
            height: 80px;
            object-fit: contain;
        }

        nav {
            background-color: #f8f6ee;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            border-bottom: 1px solid #e2dfd4;
        }

        nav a {
            margin: 0 20px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* Tiêu đề trang */
        .title-section {
            background-color: #f2efe4;
            text-align: center;
            font-weight: bold;
            padding: 12px 0;
            font-size: 18px;
            border-bottom: 2px solid #ddd;
        }

        /* Khung thanh toán */
        .payment-box {
            max-width: 800px;
            background-color: #b89053;
            margin: 40px auto;
            border-radius: 10px;
            padding: 25px 35px;
            color: #000;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .payment-row b {
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            font-size: 17px;
            margin-top: 10px;
        }

        /* Phương thức thanh toán */
        .method-box {
            max-width: 800px;
            background-color: #b89053;
            margin: 20px auto 40px;
            border-radius: 10px;
            padding: 25px 35px;
            color: #000;
        }

        .method-box b {
            font-size: 17px;
        }

        .method-item {
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        /* Nút xác nhận */
        .confirm-btn {
            display: block;
            width: 150px;
            background-color: #b89053;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            margin: 0 auto 50px;
            transition: 0.3s;
        }

        .confirm-btn:hover {
            background-color: #a07a3f;
        }

        /* Footer */
        footer {
            background-color: #fff;
            color: #000;
            font-size: 13px;
            padding: 25px 40px;
            text-align: left;
            border-top: 2px solid #ddd;
        }

        footer .footer-logo {
            text-align: left;
            margin-bottom: 15px;
        }

        footer .footer-logo img {
            height: 70px;
        }

        footer .links {
            text-align: left;
            margin-bottom: 8px;
        }

        footer .links a {
            text-decoration: none;
            color: #000;
            margin: 15px;
        }

        footer p {
            margin-bottom: 6px;
            line-height: 1.4;
            text-align: left;
        }

        footer .company-info {
            margin-top: 10px;
            text-align: left;
        }

        footer .copyright {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
            text-align: left;
        }
        
    </style>

    <style>
    /* Popup overlay */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    /* Popup nội dung */
    .popup {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        width: 90%;
        max-width: 400px;
        text-align: center;
    }

    .popup input[type="text"] {
        width: 100%;
        padding: 8px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .popup button {
        margin-top: 10px;
        padding: 8px 15px;
        background: #b89053;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .popup button:hover {
        background: #a07a3f;
    }

    .popup .list {
        margin-top: 10px;
        text-align: left;
    }

    .popup .list div {
        background: #f3f1eb;
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 5px;
        cursor: pointer;
    }

    .popup .list div:hover {
        background: #e2dbc8;
    }

    .popup .list div.selected {
    background: #b89053;
    color: white;
    font-weight: bold;
    }
    </style>

<script>
// Mở popup
function openPopup(id) {
    document.getElementById(id).style.display = 'flex';
}

// Đóng popup
function closePopup(id) {
    document.getElementById(id).style.display = 'none';
}

// Chọn 1 item trong popup
function selectItem(popupId, value, displayId) {
    // Xóa highlight cũ
    const popup = document.getElementById(popupId);
    popup.querySelectorAll('.list div').forEach(div => div.classList.remove('selected'));

    // Tìm item được click và highlight
    const selected = Array.from(popup.querySelectorAll('.list div')).find(div => div.textContent === value);
    if (selected) selected.classList.add('selected');

    // Hiển thị ra ngoài
    document.getElementById(displayId).innerText = value;

    // Đóng popup
    closePopup(popupId);
}
</script>

</head>
<body>

    <!-- Thanh trên cùng -->
    <div class="top-bar">
        <div><a href="#">CINEMA Facebook</a></div>
        <div>
            <a href="#">USER</a> |
            <a href="#">Thẻ thành viên</a> |
            <a href="#">Hỗ trợ khách hàng</a> |
            <button style="background:#ccc;padding:2px 8px;border:none;border-radius:3px;">English</button>
        </div>
    </div>

    <!-- Logo + menu -->
    <header>
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
    </header>

    <nav>
        <a href="#">SHOP QUÀ TẶNG</a>
        <a href="#">MUA VÉ</a>
        <a href="#">PHIM</a>
        <a href="#">RẠP CHIẾU PHIM</a>
        <a href="#">TIN MỚI & ƯU ĐÃI</a>
        <a href="#">LIÊN HỆ</a>
    </nav>

    <div class="title-section">
        THANH TOÁN
    </div>

    <!-- Thông tin thanh toán -->
    <form action="process_payment.php" method="POST">
        <div class="payment-box">
            <div class="payment-row">Phim <span><b>Tên Phim</b></span></div>
            <div class="payment-row">Suất chiếu <span><b>12/10/2025 - 14:15</b></span></div>
            <div class="payment-row">Rạp <span><b>CGV Vincom Mỹ Tho</b></span></div>
            <div class="payment-row">Phòng chiếu <span><b>Cinema 2</b></span></div>
            <div class="payment-row">Ghế <span><b>F06, F07</b></span></div>
            <div class="payment-row">Giá vé <span><b>181.000đ</b></span></div>
            <div class="payment-row">Người đặt <span><b>Nguyễn Văn A</b></span></div>
            <div class="payment-row">Số điện thoại <span><b>0909xxxxxx</b></span></div>
            <div class="payment-row">Email <span><b>email@gmail.com</b></span></div>
            <div class="payment-row">Tạm tính <span><b>181.000đ</b></span></div>

<div class="payment-row">
    Ưu đãi 
    <span>
        <b style="cursor:pointer;color:blue;" onclick="openPopup('popup-ma')">
            Chọn hoặc nhập mã >
        </b>
        <div id="selected-ma" style="font-size:14px;color:#fff;margin-top:5px;"></div>
    </span>
</div>

<!-- Popup nhập mã ưu đãi -->
<div id="popup-ma" class="popup-overlay">
    <div class="popup">
        <h3>Nhập mã ưu đãi</h3>
        <input type="text" placeholder="Nhập mã tại đây...">
        <div class="list">
<div onclick="selectItem('popup-ma','GIAM10K','selected-ma')">GIAM10K</div>
<div onclick="selectItem('popup-ma','GIAM20%','selected-ma')">GIAM20%</div>
        </div>
        <p>Vui lòng nhập mã khuyến mãi</p>
        <button type="button" onclick="closePopup('popup-ma')">Đóng</button>
    </div>
</div>
            <div class="payment-row total">Tổng tiền <span><b>181.000đ</b></span></div>

            <!-- Input ẩn -->
            <input type="hidden" name="movie_name" value="Tên Phim">
            <input type="hidden" name="showtime" value="12/10/2025 - 14:15">
            <input type="hidden" name="cinema" value="CGV Vincom Mỹ Tho">
            <input type="hidden" name="room" value="Cinema 2">
            <input type="hidden" name="seats" value="F06, F07">
            <input type="hidden" name="amount" value="181000">
            <input type="hidden" name="customer_name" value="Nguyễn Văn A">
            <input type="hidden" name="phone" value="0909xxxxxx">
            <input type="hidden" name="email" value="email@gmail.com">
        </div>

<!-- Phương thức thanh toán -->
<div class="method-box">
    <b>Phương thức thanh toán</b>

    <div class="method-item">
        <input type="radio" id="momo" name="payment_method" value="Momo" required>
        <label for="momo">Momo</label>
    </div>

    <div class="method-item">
        <input type="radio" id="zalopay" name="payment_method" value="Zalopay">
        <label for="zalopay">Zalopay</label>
    </div>

<div class="method-item">
    <input type="radio" id="wallet" name="payment_method" value="Ví điện tử khác">
<label for="wallet" style="cursor:pointer;color:blue;" onclick="openPopup('popup-wallet')">Ví điện tử khác ></label>
<div id="selected-wallet" style="font-size:14px;color:#fff;margin-left:25px;margin-top:5px;"></div>
</div>

<div class="method-item">
    <input type="radio" id="bank" name="payment_method" value="Ngân hàng khác">
<label for="bank" style="cursor:pointer;color:blue;" onclick="openPopup('popup-bank')">Ngân hàng khác ></label>
<div id="selected-bank" style="font-size:14px;color:#fff;margin-left:25px;margin-top:5px;"></div>
</div>

<!-- Popup ví điện tử -->
<div id="popup-wallet" class="popup-overlay">
    <div class="popup">
        <h3>Chọn ví điện tử</h3>
        <input type="text" placeholder="Nhập tên ví điện tử...">
        <div class="list">
<div onclick="selectItem('popup-wallet','ShoppePay','selected-wallet')">ShoppePay</div>
<div onclick="selectItem('popup-wallet','VNPay','selected-wallet')">VNPay</div>
<div onclick="selectItem('popup-wallet','PayPal','selected-wallet')">PayPal</div>
<div onclick="selectItem('popup-wallet','ZingPay','selected-wallet')">ZingPay</div>
        </div>
<button type="button" onclick="closePopup('popup-wallet')">Đóng</button>
    </div>
</div>

<!-- Popup ngân hàng -->
<div id="popup-bank" class="popup-overlay">
    <div class="popup">
        <h3>Chọn ngân hàng</h3>
        <input type="text" placeholder="Nhập tên ngân hàng...">
        <div class="list">
<div onclick="selectItem('popup-bank','BIDV','selected-bank')">BIDV</div>
<div onclick="selectItem('popup-bank','Vietcombank','selected-bank')">Vietcombank</div>
<div onclick="selectItem('popup-bank','ACB','selected-bank')">ACB</div>
<div onclick="selectItem('popup-bank','Techcombank','selected-bank')">Techcombank</div>
        </div>
<button type="button" onclick="closePopup('popup-bank')">Đóng</button>
    </div>
</div>
</div>

        <!-- Nút xác nhận -->
        <button type="submit" class="confirm-btn">Xác nhận</button>
    </form>
    
    <!-- Footer -->
    <footer>
        <div class="footer-logo">
            <img src="img/logo.png" alt="CINEMA Logo">
        </div>

        <div class="links">
            <a href="#">Chính Sách Khách Hàng Thường Xuyên</a> |
            <a href="#">Chính Sách Bảo Mật Thông Tin</a> |
            <a href="#">Điều Khoản Sử Dụng</a>
        </div>

        <div class="company-info">
            <p><b>CÔNG TY TNHH CINEMA VIỆT NAM</b></p>
            <p>Giấy CNĐKDN: 0302755928, đăng ký lần đầu ngày 02/05/2008, đăng ký thay đổi lần thứ 10 ngày 30/03/2018,<br>
            cấp bởi Sở KHĐT Thành phố Hồ Chí Minh</p>
            <p>Địa chỉ: Tầng 3, TTTM CINEMA, số 469 đường Nguyễn Hữu Thọ, Phường Tân Hưng, Quận 7, TPHCM, Việt Nam</p>
            <p>Hotline: (028) 3775 2524</p>
            <p class="copyright">COPYRIGHT © CINEMA.COM - ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

</body>
</html>
@endsection