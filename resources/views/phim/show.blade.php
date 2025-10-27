@extends('layouts.guest')
@section('title', $film->ten_phim)

@section('content')
<style>
    body { background-color: #f9f6ef; }

    .film-detail {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
    }

    .film-detail img {
        width: 300px;
        border-radius: 8px;
        object-fit: cover;
    }

    .film-info {
        flex: 1;
    }

    .film-info h2 {
        color: #3b2a19;
        margin-bottom: 10px;
        font-size: 26px;
    }

    .film-info p { margin-bottom: 8px; color: #444; }

    .film-info .btn {
        background: #b89053;
        color: #fff;
        padding: 8px 18px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
        transition: 0.3s;
    }

    .film-info .btn:hover {
        background: #a0783e;
        transform: translateY(-2px);
    }

    iframe {
        width: 100%;
        height: 420px;
        border-radius: 10px;
        margin: 25px auto 60px;
        display: block;
    }
</style>

<div class="film-detail">
    <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->ten_phim }}">
    <div class="film-info">
        <h2>{{ $film->ten_phim }}</h2>
        <p><strong>Thể loại:</strong> {{ $film->theLoai->ten }}</p>
        <p><strong>Thời lượng:</strong> {{ $film->thoi_luong }} phút</p>
        <p><strong>Ngày khởi chiếu:</strong> {{ \Carbon\Carbon::parse($film->ngay_khoi_chieu)->format('d/m/Y') }}</p>
        <p><strong>Diễn viên:</strong> {{ $film->dien_vien }}</p>
        <p><strong>Nội dung:</strong> {{ $film->mo_ta }}</p>
        <a href="{{ route('datve', $film->id) }}" class="btn">🎟️ Đặt vé ngay</a>
    </div>
</div>

@if($film->trailer)
<iframe src="{{ $film->trailer }}" frameborder="0" allowfullscreen></iframe>
@endif
@endsection
