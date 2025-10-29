@extends('layouts.layoutCustomer')

@section('title', 'Danh Sách Phim')
@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">🎬 DANH SÁCH PHIM</h3>

    {{-- Nút chuyển đổi --}}
    <div class="text-center mb-4">
        <a href="{{ route('movies.index', ['type' => 'now_showing']) }}" 
           class="btn {{ $type == 'now_showing' ? 'btn-primary' : 'btn-outline-primary' }}">
           Phim Đang Chiếu
        </a>
        <a href="{{ route('movies.index', ['type' => 'coming_soon']) }}" 
           class="btn {{ $type == 'coming_soon' ? 'btn-primary' : 'btn-outline-primary' }}">
           Phim Sắp Chiếu
        </a>
    </div>

    {{-- Danh sách phim --}}
    <div class="row">
        @forelse($movies as $movie)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ $movie->poster_url }}" 
                         alt="{{ $movie->title }}" 
                         class="card-img-top" 
                         style="height: 350px; object-fit: cover; border-radius: 8px;">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $movie->title }}</h5>
                        <p class="card-text mb-1">Thể loại: {{ $movie->genre }}</p>
                        <p class="card-text mb-1">Thời lượng: {{ $movie->duration_min }} phút</p>
                        <p class="card-text mb-1">Khởi chiếu: {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Không có phim nào trong danh mục này.</p>
        @endforelse
    </div>
</div>
@endsection
