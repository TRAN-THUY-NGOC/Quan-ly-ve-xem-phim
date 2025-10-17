@extends('layouts.app')
@section('title', 'Trang người dùng')

@section('content')
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 bg-light border-end min-vh-100 p-3">
      <h5 class="text-center mb-4">🎟️ MENU NGƯỜI DÙNG</h5>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="{{ route('user.dashboard') }}" class="nav-link active">
            <i class="bi bi-house"></i> Trang chính
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link">
            <i class="bi bi-ticket-perforated"></i> Vé của tôi
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
          </a>
        </li>
      </ul>
    </div>
    <h1>Web User</h1>
    <!-- Main content -->
    <div class="col-md-9 col-lg-10 p-4">
      <h3>Xin chào, {{ Auth::user()->name ?? Auth::user()->TenDangNhap }} 👋</h3>
      <hr>
      <div class="card mb-4">
        <div class="card-body">
          <h5>🎞️ Danh sách phim nổi bật</h5>
          <p>Xem ngay các bộ phim đang chiếu tại rạp của bạn!</p>
          <a class="btn btn-primary">Xem phim</a>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <h5>🕒 Lịch sử đặt vé</h5>
          <p>Bạn có thể xem lại các vé đã đặt gần đây.</p>
          <a class="btn btn-outline-secondary">Xem lịch sử</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
