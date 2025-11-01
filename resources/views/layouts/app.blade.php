<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','LCinema')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/cinema.css') }}">
</head>

<body class="has-fixed-top">
  {{-- TOPBAR CỐ ĐỊNH --}}
  @include('layouts.partials.topbar')

  {{-- LOGO TO GIỮA --}}
  @include('layouts.partials.masthead')

  {{-- THANH MENU THEO VAI TRÒ --}}
  @auth
    @php
      $isAdmin = optional(Auth::user()->role)->name === 'Admin' || Auth::user()->role_id === 1;
    @endphp
  
    @if($isAdmin)
      @include('layouts.partials.page_toolbarAdmin')
    @else
      @include('layouts.partials.page_toolbarCustomer')
    @endif
  @else
    {{-- Khách chưa đăng nhập: toolbar công khai (nếu muốn) --}}
    @includeWhen(View::exists('layouts.partials.public-nav'), 'layouts.partials.public-nav')
  @endauth
  

  {{-- NỘI DUNG CHÍNH --}}
  <div class="app-wrap">
    {{-- SIDEBAR (Admin hoặc User) --}}
    @hasSection('sidebar')
      @yield('sidebar')
    @endif

    <main class="content">
      @yield('content')
    </main>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Toggle dropdown user (đoạn bạn đã có)
    const btn = document.getElementById('btnUserDropdown');
    const menu = document.getElementById('userDropdownMenu');
    if (btn && menu) {
      btn.addEventListener('click', (e) => {
        e.stopPropagation(); menu.classList.toggle('show');
      });
      document.addEventListener('click', (e) => {
        if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.remove('show');
      });
    }
  
    // Toggle nav mobile (Admin & Customer)
    const btnAdmin = document.getElementById('btnAdminNav');
    const adminMenu = document.querySelector('.admin-nav .admin-menu');
    if (btnAdmin && adminMenu) {
      btnAdmin.addEventListener('click', () => {
        adminMenu.classList.toggle('d-none');
      });
    }
    const btnCus = document.getElementById('btnCustomerNav');
    const cusMenu = document.querySelector('.customer-nav .customer-menu');
    if (btnCus && cusMenu) {
      btnCus.addEventListener('click', () => {
        cusMenu.classList.toggle('d-none');
      });
    }
  });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>
</html>
