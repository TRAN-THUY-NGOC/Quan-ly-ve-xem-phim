@extends('layouts.layoutAdmin')
@section('title', 'Thông tin quản trị viên')

@section('content')
<style>
    body {
        background-color: #f8f5ef;
        font-family: 'Arial', sans-serif;
    }

    .info-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fffaf0;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo img {
        height: 60px;
    }

    .info-title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #000000ff;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .avatar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .avatar img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #bfa476;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .info-card {
        background-color: #bfa476;
        color: #222;
        border-radius: 16px;
        padding: 30px;
        font-size: 18px;
        line-height: 1.8;
    }

    .info-card strong {
        display: inline-block;
        width: 180px;
    }

    .btn-update {
        display: inline-block;
        background-color: #d62d20;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        margin-top: 40px; /* tăng khoảng cách xuống dưới */
        text-decoration: none; /* bỏ gạch chân */
        font-weight: bold;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background-color: #b31d14;
        text-decoration: none; /* đảm bảo không hiện lại gạch chân khi hover */
    }

    footer {
        text-align: center;
        font-size: 13px;
        color: #666;
        margin-top: 60px;
        padding: 20px;
        border-top: 1px solid #ddd;
    }

    /* --- THÔNG BÁO THÀNH CÔNG GIỮA MÀN HÌNH --- */
    .success-popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #4caf50;
        color: white;
        font-weight: bold;
        padding: 20px 40px;
        border-radius: 12px;
        font-size: 22px;
        opacity: 0;
        z-index: 9999;
        transition: opacity 0.6s ease;
    }
    .success-popup.show {
        opacity: 1;
    }

</style>

<div class="container-fluid">

    @if(session('success'))
        <div id="successPopup" class="success-popup">
            {{ session('success') }}
        </div>
    @endif

    <div class="info-container text-center">
        <h2 class="info-title">THÔNG TIN</h2>

        <div class="avatar">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}"
                    class="rounded-circle"
                    width="200"
                    height="200"
                    alt="Avatar">
            @else
                <img src="{{ asset('images/default-avatar.png') }}"
                    class="rounded-circle"
                    width="200"
                    height="200"
                    alt="Avatar">
            @endif
        </div>


        <div class="info-card text-start">
            <p><strong>Họ và tên:</strong> {{ Auth::user()->name ?? 'Chưa có dữ liệu' }}</p>
            <p><strong>Ngày sinh:</strong> {{ Auth::user()->birthday ?? 'Chưa cập nhật' }}</p>
            <p><strong>Số điện thoại:</strong> {{ Auth::user()->phone ?? 'Chưa cập nhật' }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email ?? 'Chưa cập nhật' }}</p>
            <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
        </div>

        <!-- Nút cập nhật -->
        <a href="{{ route('admin.editInfo') }}" class="btn btn-update">
            Cập nhật thông tin
        </a>
    </div>


</div>

<!-- JS Bootstrap (nếu layout chưa có) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("successPopup");
        if (popup) {
            popup.classList.add("show");
            setTimeout(() => {
                popup.classList.remove("show");
            }, 3000); // 3 giây biến mất
        }
    });
</script>

@endsection
