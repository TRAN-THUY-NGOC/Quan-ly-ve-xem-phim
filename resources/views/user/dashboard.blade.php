{{-- resources/views/user/dashboard.blade.php --}}

@extends('layouts.layoutCustomer')

@section('title', 'Bảng Điều Khiển Khách Hàng')
@section('page-title', 'BẢNG ĐIỀU KHIỂN') 

@section('content')
@php
    // Lấy tên route hiện tại để xác định mục active trong Menu Sidebar
    $currentRoute = Route::currentRouteName();
@endphp

<div class="row">
    
    {{-- Cột 1 (3/12): Menu Sidebar Người Dùng (Đã Gộp Code) --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <h6 class="card-title p-3 mb-0 border-bottom bg-light fw-bold text-danger">
                    <i class="bi bi-person-circle me-2"></i> MENU NGƯỜI DÙNG
                </h6>
                <ul class="list-group list-group-flush">
                    {{-- Trang chính (Dashboard) --}}
                    <li class="list-group-item @if($currentRoute == 'user.dashboard') active bg-light @endif">
                        <a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none @if($currentRoute == 'user.dashboard') fw-bold @endif">Trang chính</a>
                    </li>
                    {{-- Vé của tôi --}}
                    <li class="list-group-item @if($currentRoute == 'user.tickets') active bg-light @endif">
                        <a href="#" class="text-primary text-decoration-none">Vé của tôi</a>
                    </li>
                    {{-- Hồ sơ cá nhân --}}
                    <li class="list-group-item @if($currentRoute == 'profile.edit') active bg-light @endif">
                        <a href="{{ route('profile.profileUser') }}" class="text-primary text-decoration-none @if($currentRoute == 'profile.profileUser') fw-bold @endif">Hồ sơ cá nhân</a>
                    </li>
                    {{-- Đăng xuất --}}
                    <li class="list-group-item">
                        <form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
                            @csrf
                            <button class="btn btn-link p-0 text-danger text-decoration-none" type="submit">
                                Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    {{-- Cột 2 (9/12): Nội dung chính Dashboard --}}
    <div class="col-md-9">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="mb-4">Chào mừng trở lại, <span class="text-primary">{{ Auth::user()->name ?? 'Khách hàng' }}</span>!</h4>
                <div class="alert alert-info">
                    Đây là bảng điều khiển khách hàng. Bạn có thể xem tổng quan về vé và các ưu đãi tại đây.
                </div>
            </div>
        </div>
        
        <h5 class="mb-3">Tổng quan tài khoản</h5>
        <div class="row">
            {{-- Widget 1: Vé đã đặt --}}
            <div class="col-sm-4 mb-3">
                <div class="card text-center border-primary shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Vé đã đặt</h6>
                        {{-- Thay thế '0' bằng biến $totalTickets được truyền từ Controller --}}
                        <p class="display-6 text-primary">0</p>
                    </div>
                </div>
            </div>
            
            {{-- Widget 2: Điểm thành viên --}}
            <div class="col-sm-4 mb-3">
                <div class="card text-center border-success shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Điểm thành viên</h6>
                        {{-- Thay thế '0' bằng biến $memberPoints được truyền từ Controller --}}
                        <p class="display-6 text-success">0</p>
                    </div>
                </div>
            </div>
            
            {{-- Widget 3: Ưu đãi khả dụng --}}
            <div class="col-sm-4 mb-3">
                <div class="card text-center border-warning shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Ưu đãi</h6>
                        {{-- Thay thế '0' bằng biến $availableVouchers được truyền từ Controller --}}
                        <p class="display-6 text-warning">0</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Bảng/danh sách vé gần nhất --}}
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-white fw-bold">Vé đã đặt gần đây</div>
            <div class="card-body">
                <p class="text-muted text-center mb-0">Chưa có giao dịch nào được ghi nhận.</p>
            </div>
        </div>
    </div>
</div>
@endsection