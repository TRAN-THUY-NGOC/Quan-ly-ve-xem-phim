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
                    <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-primary">{{ $movie->title }}</h5>
                        <p class="card-text mb-1">Thể loại: {{ $movie->genre }}</p>
                        <p class="card-text mb-1">Thời lượng: {{ $movie->duration_min }} phút</p>
                        <p class="card-text mb-3">Khởi chiếu: {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</p>
                    </div>
                
                    <div class="d-flex justify-content-between mt-3">
                        <!-- Nút mở modal -->
                        <button type="button" class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#trailerModal{{ $movie->id }}">
                          🎞️ Trailer
                        </button>

                        <!-- Modal -->
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

                
                        {{-- Nút đặt vé --}}
                        <a href="#" class="btn btn-outline-secondary btn-sm" title="Chức năng đang phát triển">
                            🚧 Đặt vé (Coming soon)
                        </a>
                    </div>
                </div>
                
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Không có phim nào trong danh mục này.</p>
        @endforelse
    </div>
</div>
@endsection
