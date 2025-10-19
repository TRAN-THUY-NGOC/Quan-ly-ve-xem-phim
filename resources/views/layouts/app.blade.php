{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

  {{-- Bootstrap 5 CDN --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CSS riêng --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  <!-- 🔹 Thanh trên cùng -->
  <div class="top-bar d-flex justify-content-between align-items-center px-4 py-1" style="background:#f9f5ed; font-size:13px;">
    <div>
      <a href="#" class="text-decoration-none text-dark">CINEMA Facebook</a>
    </div>

    <div class="d-flex align-items-center gap-2">
      @guest
        <a href="{{ route('login') }}" class="text-decoration-none" style="color:#5a2d0c;">Đăng nhập</a> |
      @else
        <span style="color:#5a2d0c;">{{ Auth::user()->name }}</span> |
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-decoration-none" style="color:#5a2d0c;">Đăng xuất</button>
        </form> |
      @endguest

      <a href="#" class="text-decoration-none" style="color:#5a2d0c;">Thẻ thành viên</a> |
      <a href="#" class="text-decoration-none" style="color:#5a2d0c;">Hỗ trợ khách hàng</a> |
      <a href="#" class="text-decoration-none" style="color:#5a2d0c;">English</a>
    </div>
  </div>

  {{-- Navbar (logo, menu) --}}
  @includeIf('layouts.navigation')

  {{-- Page Heading --}}
  @isset($header)
    <header class="bg-light border-bottom">
      <div class="container py-3">
        {{ $header }}
      </div>
    </header>
  @endisset

  {{-- Page Content --}}
  <main class="container py-4">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="text-center text-muted small py-4">
    © {{ date('Y') }} Cinema
  </footer>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
