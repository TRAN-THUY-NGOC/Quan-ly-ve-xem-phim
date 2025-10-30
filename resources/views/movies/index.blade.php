@extends('layouts.app')

@section('title','Danh sách phim')

@section('content')
<div class="container py-4">
  <h3 class="mb-3">🎬 Danh sách phim đang chiếu</h3>

  @if($movies->isEmpty())
    <div class="alert alert-info">Chưa có phim nào.</div>
  @else
    <div class="row row-cols-1 row-cols-md-4 g-4">
      @foreach($movies as $movie)
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img
              src="{{ $movie->poster_url ?: 'https://via.placeholder.com/300x420?text=No+Poster' }}"
              class="card-img-top" alt="{{ $movie->title }}" />

            <div class="card-body">
              <h5 class="card-title mb-1">{{ $movie->title }}</h5>
              <div class="text-muted small mb-2">
                {{ $movie->genre ?? '—' }} • {{ $movie->duration_min ?? '…' }}p
              </div>
              @if(!empty($movie->description))
                <p class="card-text small">
                  {{ mb_strimwidth($movie->description, 0, 120, '…', 'UTF-8') }}
                </p>
              @endif
            </div>

            <div class="card-footer bg-light text-center">
              <a class="btn btn-sm btn-outline-primary"
                 href="{{ route('movies.show', $movie->id) }}">
                Xem chi tiết
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $movies->links() }}
    </div>
  @endif
</div>
@endsection
