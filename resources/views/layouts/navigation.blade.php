{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <!-- 🔹 Logo -->
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ asset('assets/images/logo.png') }}" alt="Cinema Logo" style="height:40px;">
    </a>

    <!-- 🔹 Nút mở menu khi trên mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- 🔹 Menu -->
    <div class="collapse navbar-collapse" id="topNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard') }}">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Phim</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Rạp chiếu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Khuyến mãi</a>
        </li>
      </ul>

      <!-- 🔹 Menu người dùng -->
      @auth
      <div class="dropdown">
        <a class="btn btn-outline-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
          {{ Auth::user()->full_name ?? Auth::user()->name }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Hồ sơ</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}" class="px-3">
              @csrf
              <button class="btn btn-sm btn-danger w-100" type="submit">Đăng xuất</button>
            </form>
          </li>
        </ul>
      </div>
      @else
      <a href="{{ route('login') }}" class="btn btn-outline-light ms-2">Đăng nhập</a>
      @endauth
    </div>
  </div>
</nav>
