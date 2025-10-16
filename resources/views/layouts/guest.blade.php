<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title','Trang khách')</title>

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (nếu cần icon) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- CSS riêng -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body style="background-color:#fff9f0;">
  <main class="d-flex flex-column justify-content-center align-items-center min-vh-100">
    <div class="text-center mb-4">
      <a href="/">
        {{-- thay logo nếu bạn có --}}
        <span class="fs-1 fw-bold text-danger">CINEMA</span>
      </a>
    </div>

    <div class="card shadow p-4" style="max-width: 520px; width:100%; background-color:#f6e4b6;">
      @yield('content')  {{-- <<<< quan trọng: thay cho $slot --}}
    </div>
  </main>

  <footer class="text-center py-4 small text-muted">
    Chính Sách Khách Hàng Thường Xuyên | Chính Sách Bảo Mật Thông Tin | Điều Khoản Sử Dụng
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
