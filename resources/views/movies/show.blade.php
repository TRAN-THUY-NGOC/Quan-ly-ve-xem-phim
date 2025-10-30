@extends('layouts.app')

@section('title', $movie->title)

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <div class="col-md-4">
      <img
        src="{{ $movie->poster_url ?: 'https://via.placeholder.com/400x560?text=No+Poster' }}"
        class="img-fluid rounded" alt="{{ $movie->title }}" />
    </div>

    <div class="col-md-8">
      <h2 class="mb-2">{{ $movie->title }}</h2>
      <div class="text-muted mb-3">
        <strong>Thể loại:</strong> {{ $movie->genre ?? '—' }} |
        <strong>Thời lượng:</strong> {{ $movie->duration_min ?? '…' }} phút |
        <strong>Khởi chiếu:</strong> {{ optional($movie->release_date)->format('d/m/Y') ?? '—' }}
      </div>

      @if(!empty($movie->description))
        <p class="mb-3">{{ $movie->description }}</p>
      @endif

      @if(!empty($movie->trailer_url))
        <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-danger mb-3">🎥 Xem trailer</a>
      @endif

      <div>
        <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary">⬅ Quay lại danh sách</a>
      </div>
    </div>
  </div>
</div>
@endsection
