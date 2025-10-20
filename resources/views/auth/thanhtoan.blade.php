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

    /* mỗi dòng label - value */
    .payment-box div.row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* cho value có thể flow xuống */
        gap: 12px;
        margin-bottom: 6px;
    }

    .payment-box span.label {
        color: #fff;
        font-weight: bold;
        width: 150px; /* giữ đều như thiết kế */
        flex: 0 0 150px;
    }

    .payment-box .value {
        color: #fff;
        flex: 1;
        text-align: right; /* quan trọng: căn phải */
        word-break: break-word;
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

    /* ===== hiển thị mã ưu đãi căn phải ===== */
    .discount-display {
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* căn phải cả nhóm */
        text-align: right;
        width: 100%;
    }

    .discount-display strong {
        color: #fff;
        font-weight: bold;
    }

    .discount-display .choose-again {
        color: #0047ff;
        text-decoration: underline;
        cursor: pointer;
        font-size: 14px;
        margin-top: 4px;
    }

    .link-option {
        color: #0047ff;
        text-decoration: underline;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .link-option:hover {
        color: #002f99;
    }

</style>

<div class="payment-container">
    <h2 class="page-title">THANH TOÁN</h2>

    <form method="POST" action="{{ route('thanhtoan.process') }}">
        @csrf
        <div class="payment-box">
            <div class="row"><span class="label">Phim:</span> <div class="value">{{ $order->tickets[0]->showtime->film->name }}</div></div>
            <div class="row"><span class="label">Suất chiếu:</span> <div class="value">{{ $order->tickets[0]->showtime->date }} - {{ $order->tickets[0]->showtime->time }}</div></div>
            <div class="row"><span class="label">Rạp:</span> <div class="value">{{ $order->tickets[0]->showtime->cinema->name }}</div></div>
            <div class="row"><span class="label">Phòng chiếu:</span> <div class="value">{{ $order->tickets[0]->showtime->room->name }}</div></div>
            <div class="row"><span class="label">Ghế:</span> 
                <div class="value">
                    {{ $order->tickets->pluck('seat.name')->implode(', ') }}
                </div>
            </div>
            <div class="row"><span class="label">Giá vé:</span> <div class="value">{{ number_format($order->tickets[0]->price, 0, ',', '.') }}đ</div></div>
            <div class="row"><span class="label">Người đặt:</span> <div class="value">{{ $order->user->name }}</div></div>
            <div class="row"><span class="label">Số điện thoại:</span> <div class="value">{{ $order->user->phone }}</div></div>
            <div class="row"><span class="label">Email:</span> <div class="value">{{ $order->user->email }}</div></div>
            <div class="row"><span class="label">Tạm tính:</span> <div class="value" id="subtotal">{{ number_format($order->total, 0, ',', '.') }}đ</div></div>

            <div class="row">
                <span class="label">Ưu đãi:</span>
                <!-- dùng div.value để chiếm hết phần bên phải, sau đó .discount-display căn phải bên trong -->
                <div class="value" id="discountDisplay">
                    <a href="#" id="openDiscount" style="color: #0047ff; text-decoration: underline;">Chọn hoặc nhập mã &gt;</a>
                </div>
            </div>

            <div class="total-line row">
                <span class="label">Tổng tiền:</span>
                <div class="value" id="total">{{ number_format($order->total, 0, ',', '.') }}đ</div>
            </div>
        </div>

        <div class="payment-method">
            <strong>Phương thức thanh toán</strong>
            <label><input type="radio" name="method" value="momo" checked> Momo</label>
            <label><input type="radio" name="method" value="zalopay"> ZaloPay</label>
            <label><input type="radio" name="method" value="e-wallet"> <span class="link-option">Ví điện tử khác &gt;</span></label>
            <label><input type="radio" name="method" value="bank"> <span class="link-option">Ngân hàng khác &gt;</span></label>
        </div>

        <button type="submit" class="btn-submit">Xác nhận</button>
    </form>
</div>

<!-- Popup: Mã ưu đãi -->
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

<!-- POPUP: Chọn ví điện tử -->
<div class="discount-popup" id="popupEwallet">
    <div class="discount-content">
        <h3>Chọn ví điện tử</h3>
        <input type="text" id="searchEwallet" placeholder="Tìm kiếm ví điện tử...">
        <div id="listEwallet">
            <div class="discount-option" data-name="MoMo">Paypal</div>
            <div class="discount-option" data-name="ShopeePay">ShopeePay</div>
            <div class="discount-option" data-name="VNPay">VNPay</div>
        </div>
        <p>Chọn ví bạn muốn thanh toán</p>
        <button class="closePopup">Đóng</button>
    </div>
</div>

<!-- POPUP: Chọn ngân hàng -->
<div class="discount-popup" id="popupBank">
    <div class="discount-content">
        <h3>Chọn ngân hàng</h3>
        <input type="text" id="searchBank" placeholder="Tìm kiếm ngân hàng...">
        <div id="listBank">
            <div class="discount-option" data-name="Vietcombank">Vietcombank</div>
            <div class="discount-option" data-name="Techcombank">Techcombank</div>
            <div class="discount-option" data-name="ACB">ACB</div>
            <div class="discount-option" data-name="BIDV">BIDV</div>
            <div class="discount-option" data-name="MB Bank">MB Bank</div>
        </div>
        <p>Chọn ngân hàng bạn muốn thanh toán</p>
        <button class="closePopup">Đóng</button>
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

    // lấy subtotal số nguyên từ blade (server)
    let subtotal = {!! json_encode($order->total) !!};
    let total = subtotal;

    // Mở popup
    function openDiscountPopup(e) {
        if (e) e.preventDefault();
        popup.style.display = 'flex';
    }
    openDiscount.addEventListener('click', openDiscountPopup);

    // Chọn mã trong popup
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
            newTotal = Math.max(0, Math.round(subtotal * 0.8));
            msg = 'Áp dụng thành công: Giảm 20%';
        } else {
            msg = 'Mã không hợp lệ!';
        }

        discountMessage.textContent = msg;
        totalEl.textContent = newTotal.toLocaleString('vi-VN') + 'đ';

        // Hiện mã ra ngoài màn hình chính + nút chọn lại (đặt trong .discount-display – đã căn phải qua CSS)
        discountDisplay.innerHTML = `
            <div class="discount-display">
                <strong>${code}</strong>
                <span class="choose-again" id="chooseAgain">Chọn lại mã khác &gt;</span>
            </div>
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

    // Nếu user nhập thủ công và bấm Enter (từ input trong popup), áp mã
    document.getElementById('discountInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyDiscount(this.value.trim().toUpperCase());
        }
    });

// ===== POPUP CHỌN VÍ & NGÂN HÀNG =====
const popupEwallet = document.getElementById('popupEwallet');
const popupBank = document.getElementById('popupBank');
const searchEwallet = document.getElementById('searchEwallet');
const searchBank = document.getElementById('searchBank');
const listEwallet = document.getElementById('listEwallet');
const listBank = document.getElementById('listBank');

// mở popup ví
document.querySelector('label input[value="e-wallet"]').nextElementSibling.addEventListener('click', () => {
    popupEwallet.style.display = 'flex';
});
// mở popup ngân hàng
document.querySelector('label input[value="bank"]').nextElementSibling.addEventListener('click', () => {
    popupBank.style.display = 'flex';
});

// đóng popup (chung)
document.querySelectorAll('.closePopup').forEach(btn => {
    btn.addEventListener('click', () => {
        popupEwallet.style.display = 'none';
        popupBank.style.display = 'none';
    });
});

// chọn ví điện tử
listEwallet.querySelectorAll('.discount-option').forEach(opt => {
    opt.addEventListener('click', () => {
        listEwallet.querySelectorAll('.discount-option').forEach(o => o.classList.remove('active'));
        opt.classList.add('active');
        document.querySelector('label input[value="e-wallet"]').nextElementSibling.innerHTML = `${opt.dataset.name} &gt;`;
        popupEwallet.style.display = 'none';
    });
});

// chọn ngân hàng
listBank.querySelectorAll('.discount-option').forEach(opt => {
    opt.addEventListener('click', () => {
        listBank.querySelectorAll('.discount-option').forEach(o => o.classList.remove('active'));
        opt.classList.add('active');
        document.querySelector('label input[value="bank"]').nextElementSibling.innerHTML = `${opt.dataset.name} &gt;`;
        popupBank.style.display = 'none';
    });
});

// tìm kiếm ví
searchEwallet.addEventListener('input', () => {
    const val = searchEwallet.value.toLowerCase();
    listEwallet.querySelectorAll('.discount-option').forEach(opt => {
        opt.style.display = opt.textContent.toLowerCase().includes(val) ? 'block' : 'none';
    });
});
// tìm kiếm ngân hàng
searchBank.addEventListener('input', () => {
    const val = searchBank.value.toLowerCase();
    listBank.querySelectorAll('.discount-option').forEach(opt => {
        opt.style.display = opt.textContent.toLowerCase().includes(val) ? 'block' : 'none';
    });
});
</script>
@endsection
