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
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ asset($movie->poster_url) }}" 
                         class="card-img-top" 
                         alt="{{ $movie->title }}" 
                         style="height: 350px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title text-primary fw-bold">{{ $movie->title }}</h6>
                        <p class="text-muted small mb-1">Thể loại: {{ $movie->genre }}</p>
                        <p class="text-muted small mb-1">Thời lượng: {{ $movie->duration_min }} phút</p>
                        <p class="text-muted small">Khởi chiếu: {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 text-center">
                        <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline-danger btn-sm">
                            Xem Trailer
                        </a>
                        <a href="#" class="btn btn-primary btn-sm">Đặt Vé</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Không có phim nào trong danh mục này.</p>
        @endforelse
    </div>
</div>
@endsection
