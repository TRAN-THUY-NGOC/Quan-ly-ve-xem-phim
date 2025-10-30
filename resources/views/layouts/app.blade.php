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

  {{-- THANH MENU NGANG (page toolbar) --}}
  @include('layouts.partials.page_toolbar')

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
</body>
</html>
