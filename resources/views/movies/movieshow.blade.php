@extends('layouts.layoutCustomer')

@section('title', $movie->title)

@section('content')
<div class="container py-4">
    <h4 class="text-primary fw-bold mb-4">🎬 CHI TIẾT PHIM</h4>

    {{-- Ảnh chính và carousel --}}
    <div class="text-center mb-4">
        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" 
             class="rounded shadow" style="max-width: 70%; height: auto;">
    </div>

    {{-- Thông tin phim --}}
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="{{ $movie->poster_url }}" class="img-fluid rounded mb-3" style="max-height: 400px;">
            <a href="#" class="btn btn-danger w-75">🎟️ Đặt vé</a>
        </div>

        <div class="col-md-8">
            <h3 class="fw-bold text-dark">{{ $movie->title }}</h3>
            <p class="mb-1">⭐ <strong>Xếp hạng:</strong> 9.7</p>
            <p class="mb-1">🎭 <strong>Thể loại:</strong> {{ $movie->genre }}</p>
            <p class="mb-1">⏱️ <strong>Thời lượng:</strong> {{ $movie->duration_min }} phút</p>
            <p class="mb-3">📅 <strong>Khởi chiếu:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>

            <h5 class="mt-4 fw-bold">📖 Tóm tắt</h5>
            <p>{{ $movie->description }}</p>
        </div>
    </div>

    {{-- Trailer --}}
    @if ($movie->trailer_url)
        @php
            $trailerEmbed = str_replace('watch?v=', 'embed/', $movie->trailer_url);
            $trailerEmbed = str_replace('youtu.be/', 'www.youtube.com/embed/', $trailerEmbed);
        @endphp
        <div class="mt-5">
            <h5 class="fw-bold mb-3">🎞️ Trailer</h5>
            <div class="ratio ratio-16x9">
                <iframe src="{{ $trailerEmbed }}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    @endif

    {{-- Xếp hạng và bình luận --}}
    <div class="mt-5">
        <h5 class="fw-bold mb-3">⭐ Xếp hạng và đánh giá phim</h5>
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <span class="text-warning fs-4">★★★★★</span>
            </div>
            <input type="text" class="form-control w-50 me-2" placeholder="Nhập đánh giá...">
            <button class="btn btn-dark">Bình luận</button>
        </div>
        <p class="text-muted">0 điểm</p>
    </div>

    {{-- Lưu ý & footer nhỏ --}}
    <div class="alert alert-secondary mt-4">
        ⚠️ <strong>Lưu ý:</strong> Vui lòng kiểm tra lại lịch chiếu trước khi đặt vé.
    </div>

    <div class="text-center mt-3">
        <a href="#" class="btn btn-primary">🎫 Mua vé</a>
    </div>
</div>
@endsection
