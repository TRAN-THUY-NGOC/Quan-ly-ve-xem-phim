@extends('layouts.layoutCustomer')

@section('title', 'Danh Sách Phim')
@section('content')
<div class="text-center mb-4">
    <a href="{{ route('booking.history') }}" class="btn btn-success">
        🧾 Lịch sử đặt vé
    </a>
</div>

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
                <div class="card h-100 position-relative movie-card">
                    <img src="{{ $movie->poster_url }}" 
                         alt="{{ $movie->title }}" 
                         class="card-img-top movie-poster" 
                         style="height: 350px; object-fit: cover; border-radius: 8px; cursor: pointer;">

                    {{-- Các nút hiển thị khi click ảnh --}}
                    <div class="movie-actions text-center">
                        <a href="{{ route('movies.movieshow', $movie->id) }}" class="btn btn-primary btn-sm">📖 Chi tiết</a>
                        <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#trailerModal{{ $movie->id }}">
                            🎞 Trailer
                        </button>
                        @if (\Carbon\Carbon::parse($movie->release_date)->isPast())
                            <a href="{{ route('booking.form', $movie->id) }}" class="btn btn-secondary btn-sm">🎟 Đặt vé</a>
                        @endif

                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-primary">{{ $movie->title }}</h5>
                            <p class="card-text mb-1">Thể loại: {{ $movie->genre }}</p>
                            <p class="card-text mb-1">Thời lượng: {{ $movie->duration_min }} phút</p>
                            <p class="card-text mb-3">Khởi chiếu: {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Trailer -->
                <div class="modal fade" id="trailerModal{{ $movie->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">{{ $movie->title }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                @php
                                    $trailerEmbed = str_replace('watch?v=', 'embed/', $movie->trailer_url);
                                    $trailerEmbed = str_replace('youtu.be/', 'www.youtube.com/embed/', $trailerEmbed);
                                @endphp
                                <iframe width="100%" height="400"
                                        src="{{ $trailerEmbed }}"
                                        frameborder="0"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <p class="text-center text-muted">Không có phim nào trong danh mục này.</p>
        @endforelse
    </div>
</div>

{{-- JS bật/tắt các nút khi bấm ảnh --}}
<script>
document.querySelectorAll('.movie-poster').forEach(img => {
    img.addEventListener('click', () => {
        const parent = img.closest('.movie-card');
        const actions = parent.querySelector('.movie-actions');
        actions.classList.toggle('show');
    });
});
</script>

{{-- CSS nội bộ hoặc tách file riêng --}}
<style>
.movie-card {
    overflow: hidden;
    position: relative;
}
.movie-actions {
    display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    gap: 10px;
}
.movie-actions.show {
    display: flex;
}
</style>
@endsection
