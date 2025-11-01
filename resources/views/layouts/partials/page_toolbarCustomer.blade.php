{{-- resources/views/layouts/navs/customer-nav.blade.php --}}
<nav class="admin-nav">
  <div class="container d-flex align-items-center">

    {{-- hamburger chỉ hiện trên mobile --}}
    <button id="btnCustomerNav" class="btn-hamburger d-md-none me-2" aria-label="Menu">
      <i class="bi bi-list" style="font-size:18px"></i>
    </button>

    <ul class="admin-menu">
      <li class="{{ request()->is('/') ? 'active' : '' }}">
        <a href="{{ url('/') }}">Trang chủ</a>
      </li>
      <li class="{{ request()->is('movies*') ? 'active' : '' }}">
        <a href="{{ url('/movies') }}">Phim</a>
      </li>
      <li class="{{ request()->is('showtimes*') ? 'active' : '' }}">
        <a href="{{ url('/showtimes') }}">Lịch chiếu</a>
      </li>
      <li class="{{ request()->is('prices*') ? 'active' : '' }}">
        <a href="{{ url('/prices') }}">Giá vé</a>
      </li>
      <li class="{{ request()->is('tickets*') ? 'active' : '' }}">
        <a href="{{ url('/tickets') }}">Vé của tôi</a>
      </li>
      <li class="{{ request()->is('promos*') ? 'active' : '' }}">
        <a href="{{ url('/promos') }}">Khuyến mãi</a>
      </li>
      <li class="{{ request()->is('profile*') ? 'active' : '' }}">
        <a href="{{ url('/profile') }}">Tài khoản</a>
      </li>
    </ul>
  </div>
</nav>
