{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  {{-- Fonts (tuỳ chọn) --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

  {{-- Bootstrap 5 CDN thay cho @vite --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- CSS riêng --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  {{-- Navbar (thay cho layouts.navigation bản Tailwind) --}}
  @includeIf('layouts.navigation') {{-- bạn sẽ đổi file này sang Bootstrap ở dưới --}}

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
    {{ $slot }} {{-- giữ nguyên vì đây là layout component --}}
  </main>

  {{-- Footer đơn giản --}}
  <footer class="text-center text-muted small py-4">
    © {{ date('Y') }} Cinema
  </footer>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
