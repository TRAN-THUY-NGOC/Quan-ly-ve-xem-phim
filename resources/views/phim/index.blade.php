@extends('layouts.guest')

@section('title', 'Tra cứu phim')

@section('content')
<style>
    body { background-color: #f9f6ef; }

    /* --- Tiêu đề trang --- */
    .page-title {
        text-align: center;
        font-weight: bold;
        font-size: 28px;
        color: #3b2a19;
        margin-top: 40px;
        margin-bottom: 20px;
    }

    /* --- Form tìm kiếm bên phải --- */
    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-right: 60px;
        margin-bottom: 40px;
    }

    .search-container form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search-container input,
    .search-container select {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .search-container button {
        background-color: #b89053;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-container button:hover {
        background-color: #a0783e;
    }

    /* --- Lưới phim --- */
    .film-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
        max-width: 1100px;
        margin: 20px auto 60px;
    }

    .film-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
    }

    .film-card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }

    .film-card h3 {
        margin: 10px 0 5px;
        color: #3b2a19;
        font-weight: bold;
    }

    .film-card p {
        padding: 0 10px;
        font-size: 14px;
        color: #555;
    }

    .film-card a {
        display: inline-block;
        margin: 10px 0 15px;
        background: #b89053;
        color: #fff;
        padding: 6px 14px;
        border-radius: 5px;
        text-decoration: none;
    }

    .film-card a:hover { background: #a0783e; }
</style>

<!-- Tiêu đề ở giữa -->
<h2 class="page-title">TRA CỨU PHIM</h2>

<!-- Form tìm kiếm ở góc phải -->
<div class="search-container">
    <form method="GET" action="{{ route('phim.index') }}">
        <input type="text" name="ten" placeholder="Nhập tên phim..." value="{{ request('ten') }}">
        <select name="the_loai">
            <option value="">-- Thể loại --</option>
            @foreach($genres as $g)
                <option value="{{ $g->id }}" {{ request('the_loai') == $g->id ? 'selected' : '' }}>
                    {{ $g->name }}
                </option>
            @endforeach
        </select>
        <input type="date" name="ngay_khoi_chieu" value="{{ request('ngay_khoi_chieu') }}">
        <button type="submit">Tìm kiếm</button>
    </form>
</div>

<!-- Danh sách phim -->
<div class="film-grid">
    @foreach($films as $film)
        <div class="film-card">
            <img src="{{ asset('storage/' . $film->poster) }}" alt="{{ $film->ten_phim }}">
            <h3>{{ $film->ten_phim }}</h3>
            <p><strong>Thể loại:</strong> {{ $film->theLoai->ten }}</p>
            <p>{{ Str::limit($film->mo_ta, 80) }}</p>
            <a href="{{ route('phim.show', $film->id) }}">Xem chi tiết</a>
        </div>
    @endforeach
</div>
@endsection
