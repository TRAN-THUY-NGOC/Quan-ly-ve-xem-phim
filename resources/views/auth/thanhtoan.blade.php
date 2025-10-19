@extends('layouts.guest')

@section('title', 'Thanh toán')

@section('content')
<style>
    body {
        background-color: #f9f6ef;
    }

    .payment-container {
        max-width: 800px;
        margin: 30px auto;
        background-color: #f9f6ef;
        padding: 20px;
    }

    .payment-box {
        background-color: #b89053;
        color: #fff;
        padding: 20px 30px;
        border-radius: 10px;
        font-size: 16px;
        line-height: 1.7;
        position: relative;
    }

    .payment-box span.label {
        display: inline-block;
        width: 150px;
        color: #fff;
        font-weight: bold;
    }

    .payment-box span.value {
        color: #fff;
    }

    .total-line {
        border-top: 1px solid #fff;
        margin-top: 10px;
        padding-top: 10px;
        font-weight: bold;
    }

    .payment-method {
        background-color: #b89053;
        color: #fff;
        padding: 20px 30px;
        border-radius: 10px;
        margin-top: 30px;
    }

    .payment-method label {
        display: block;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .payment-method input[type="radio"] {
        margin-right: 10px;
        transform: scale(1.2);
    }

    .btn-submit {
        display: block;
        margin: 30px auto 0;
        background-color: #c3ad88;
        color: #000;
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-submit:hover {
        background-color: #b39063;
    }

    h2.page-title {
        text-align: center;
        color: #3b2a19;
        margin-bottom: 25px;
        font-weight: bold;
    }

    /* ==== POPUP ==== */
    .discount-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .discount-content {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        width: 350px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.3);
    }

    .discount-content h3 {
        margin-bottom: 15px;
        color: #000;
        font-weight: bold;
    }

    .discount-content input {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin-bottom: 15px;
    }

    .discount-option {
        background: #f2f0eb;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .discount-option.active {
        background: #b89053;
        color: #fff;
    }

    .discount-content p {
        color: #333;
        margin: 10px 0;
    }

    .discount-content button {
        background: #b89053;
        color: #fff;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .discount-content button:hover {
        background: #a0783e;
    }

    .choose-again {
        color: #0047ff;
        text-decoration: underline;
        cursor: pointer;
        font-size: 14px;
        margin-left: 10px;
    }
</style>

<div class="payment-container">
    <h2 class="page-title">THANH TOÁN</h2>

    <form method="POST" action="{{ route('thanhtoan.process') }}">
        @csrf
        <div class="payment-box">
            <div><span class="label">Phim:</span> <span class="value">{{ $order->tickets[0]->showtime->film->name }}</span></div>
            <div><span class="label">Suất chiếu:</span> <span class="value">{{ $order->tickets[0]->showtime->date }} - {{ $order->tickets[0]->showtime->time }}</span></div>
            <div><span class="label">Rạp:</span> <span class="value">{{ $order->tickets[0]->showtime->cinema->name }}</span></div>
            <div><span class="label">Phòng chiếu:</span> <span class="value">{{ $order->tickets[0]->showtime->room->name }}</span></div>
            <div><span class="label">Ghế:</span> 
                <span class="value">
                    {{ $order->tickets->pluck('seat.name')->implode(', ') }}
                </span>
            </div>
            <div><span class="label">Giá vé:</span> <span class="value">{{ number_format($order->tickets[0]->price, 0, ',', '.') }}đ</span></div>
            <div><span class="label">Người đặt:</span> <span class="value">{{ $order->user->name }}</span></div>
            <div><span class="label">Số điện thoại:</span> <span class="value">{{ $order->user->phone }}</span></div>
            <div><span class="label">Email:</span> <span class="value">{{ $order->user->email }}</span></div>
            <div><span class="label">Tạm tính:</span> <span class="value" id="subtotal">{{ number_format($order->total, 0, ',', '.') }}đ</span></div>

            <div>
                <span class="label">Ưu đãi:</span> 
                <span class="value" id="discountDisplay">
                    <a href="#" id="openDiscount" style="color: #0047ff; text-decoration: underline;">Chọn hoặc nhập mã ></a>
                </span>
            </div>

            <div class="total-line">
                <span class="label">Tổng tiền:</span> 
                <span class="value" id="total">{{ number_format($order->total, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <div class="payment-method">
            <strong>Phương thức thanh toán</strong>
            <label><input type="radio" name="method" value="momo" checked> Momo</label>
            <label><input type="radio" name="method" value="zalopay"> ZaloPay</label>
            <label><input type="radio" name="method" value="e-wallet"> Ví điện tử khác</label>
            <label><input type="radio" name="method" value="bank"> Ngân hàng khác</label>
        </div>

        <button type="submit" class="btn-submit">Xác nhận</button>
    </form>
</div>

<!-- POPUP -->
<div class="discount-popup" id="discountPopup">
    <div class="discount-content">
        <h3>Nhập mã ưu đãi</h3>
        <input type="text" id="discountInput" placeholder="Nhập mã tại đây...">
        <div class="discount-option" data-code="GIAM10K">GIAM10K</div>
        <div class="discount-option" data-code="GIAM20%">GIAM20%</div>
        <p id="discountMessage">Vui lòng nhập mã khuyến mãi</p>
        <button id="closePopup">Đóng</button>
    </div>
</div>

<script>
    const openDiscount = document.getElementById('openDiscount');
    const popup = document.getElementById('discountPopup');
    const options = document.querySelectorAll('.discount-option');
    const discountInput = document.getElementById('discountInput');
    const discountMessage = document.getElementById('discountMessage');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    const discountDisplay = document.getElementById('discountDisplay');
    const closePopupBtn = document.getElementById('closePopup');

    let subtotal = {{ $order->total }};
    let total = subtotal;

    // Mở popup
    function openDiscountPopup(e) {
        if (e) e.preventDefault();
        popup.style.display = 'flex';
    }
    openDiscount.addEventListener('click', openDiscountPopup);

    // Chọn mã ưu đãi
    options.forEach(option => {
        option.addEventListener('click', () => {
            options.forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            discountInput.value = option.dataset.code;
            applyDiscount(option.dataset.code);
        });
    });

    // Đóng popup
    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    // Áp dụng mã
    function applyDiscount(code) {
        let newTotal = subtotal;
        let msg = '';

        if (code === 'GIAM10K') {
            newTotal = Math.max(0, subtotal - 10000);
            msg = 'Áp dụng thành công: Giảm 10.000đ';
        } else if (code === 'GIAM20%') {
            newTotal = Math.max(0, subtotal * 0.8);
            msg = 'Áp dụng thành công: Giảm 20%';
        } else {
            msg = 'Mã không hợp lệ!';
        }

        discountMessage.textContent = msg;
        totalEl.textContent = newTotal.toLocaleString('vi-VN') + 'đ';

        // Hiện mã ra ngoài màn hình chính + nút chọn lại
        discountDisplay.innerHTML = `
            <strong style="color:#fff;">${code}</strong>
            <span class="choose-again" id="chooseAgain">Chọn lại mã khác</span>
        `;

        // Gắn lại sự kiện mở popup cho nút “Chọn lại mã khác”
        document.getElementById('chooseAgain').addEventListener('click', openDiscountPopup);

        // Tự đóng popup
        popup.style.display = 'none';
    }

    // Đóng popup khi click ra ngoài
    popup.addEventListener('click', (e) => {
        if (e.target === popup) popup.style.display = 'none';
    });
</script>
@endsection
