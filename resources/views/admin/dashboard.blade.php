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
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background-color: #ddd;
        margin: 0 auto 20px;
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
        background-color: #d62d20;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .btn-update:hover {
        background-color: #b31d14;
    }

    footer {
        text-align: center;
        font-size: 13px;
        color: #666;
        margin-top: 60px;
        padding: 20px;
        border-top: 1px solid #ddd;
    }
</style>

<div class="container-fluid">

    <div class="info-container text-center">
        <h2 class="info-title">THÔNG TIN</h2>

        <div class="avatar">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                    alt="Avatar" class="rounded-circle" width="180" height="180">
            @else
                <img src="https://via.placeholder.com/180x180.png?text=Avatar" 
                    alt="Avatar" class="rounded-circle">
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
        <button class="btn btn-update" data-bs-toggle="modal" data-bs-target="#updateModal">
            Cập nhật thông tin
        </button>
    </div>


</div>

<!-- JS Bootstrap (nếu layout chưa có) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
