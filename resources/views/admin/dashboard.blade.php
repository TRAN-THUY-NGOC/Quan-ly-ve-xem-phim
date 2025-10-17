@extends('layouts.app')
@section('title', 'Bảng điều khiển quản trị')

@section('content')
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 bg-dark text-white min-vh-100 p-3">
      <h5 class="text-center mb-4">🎬 QUẢN TRỊ HỆ THỐNG</h5>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link text-white active">
            <i class="bi bi-speedometer2"></i> Bảng điều khiển
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white">
            <i class="bi bi-film"></i> Quản lý phim
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white">
            <i class="bi bi-clock-history"></i> Lịch chiếu
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white">
            <i class="bi bi-ticket-perforated"></i> Vé & Giao dịch
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white">
            <i class="bi bi-people"></i> Người dùng
          </a>
        </li>
        <li class="nav-item mt-2">
          <a href="{{ route('logout') }}" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
          </a>
        </li>
      </ul>
    </div>

    <!-- Main content -->
    <div class="col-md-9 col-lg-10 p-4">
      <h3 class="text-primary">Xin chào, Quản trị viên {{ Auth::user()->name ?? '' }}</h3>
      <hr>
      <div class="row text-center">
        <div class="col-md-4 mb-3">
          <div class="card border-primary">
            <div class="card-body">
              <h6>Tổng số phim</h6>
              <p class="display-6">{{ $totalMovies ?? '--' }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-success">
            <div class="card-body">
              <h6>Vé đã bán hôm nay</h6>
              <p class="display-6">{{ $ticketsToday ?? '--' }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-warning">
            <div class="card-body">
              <h6>Doanh thu hôm nay</h6>
              <p class="display-6">{{ $revenueToday ?? '0' }}₫</p>
            </div>
          </div>
        </div>
      </div>
      <div class="card mt-4">
        <div class="card-body">
          <h5>📈 Biểu đồ doanh thu (chưa gắn chart)</h5>
          <p>Bạn có thể thêm Chart.js hoặc Google Charts sau.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
