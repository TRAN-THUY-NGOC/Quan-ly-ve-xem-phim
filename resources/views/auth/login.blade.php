@extends('layouts.guest')
@section('title','Đăng nhập')

@section('content')
<div class="login-page">

  {{-- Logo --}}
  <div class="text-center my-3 brand">
    <span class="logo-box me-2">▶</span>
    <span class="brand-text">CINEMA</span>
  </div>

  {{-- Tiêu đề dải ngang --}}
  <div class="section-title text-center">ĐĂNG NHẬP</div>

  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="login-card p-4 p-md-5">
          <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Tên đăng nhập --}}
            <div class="row align-items-center mb-4">
              <label class="col-md-4 col-form-label form-label-lg fw-bold mb-2 mb-md-0">
                Tên đăng nhập:
              </label>
              <div class="col-md-8">
                <input id="email" name="email" type="text" class="form-control form-control-lg input-soft"
                       placeholder="Email hoặc số điện thoại" value="{{ old('email') }}" required autofocus>
              </div>
            </div>

            {{-- Mật khẩu --}}
            <div class="row align-items-center mb-4">
              <label class="col-md-4 col-form-label form-label-lg fw-bold mb-2 mb-md-0">Mật khẩu:</label>
              <div class="col-md-8">
                <div class="input-group input-group-lg">
                  <input id="password" name="password" type="password" class="form-control input-soft" required>
                  <button class="btn btn-outline-secondary" type="button" id="togglePass">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                </div>
              </div>
            </div>


            {{-- Ghi nhớ --}}
            <div class="row mb-4">
              <div class="col-md-8 offset-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember">
                  <label class="form-check-label fw-semibold" for="remember_me">Ghi nhớ tôi</label>
                </div>
              </div>
            </div>

            {{-- Nút --}}
            <div class="row">
              <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-danger px-4">Đăng nhập</button>
              </div>
            </div>

            {{-- Link đăng ký --}}
            <p class="mt-3 ms-md-4">
              Chưa có tài khoản? <a href="{{ route('register') }}" class="fw-bold link-dark">Đăng ký</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="site-footer text-center small text-muted">
    <p>Chính Sách Khách Hàng Thường Xuyên &nbsp;|&nbsp; Chính Sách Bảo Mật Thông Tin &nbsp;|&nbsp; Điều Khoản Sử Dụng</p>
  </footer>
</div>
@endsection
