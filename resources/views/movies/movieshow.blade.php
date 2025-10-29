@extends('layouts.layoutCustomer')

@section('title', $movies->title)

@section('content')
<div class="container py-4">
    <h4 class="text-primary fw-bold mb-4">🎬 CHI TIẾT PHIM</h4>

    <div class="text-center mb-4">
        <img src="{{ $movies->poster_url }}" alt="{{ $movies->title }}" 
             class="rounded shadow" style="max-width: 70%; height: auto;">
    </div>

    <div class="row">
        <div class="col-md-4 text-center">
            <img src="{{ $movies->poster_url }}" class="img-fluid rounded mb-3" style="max-height: 280px;">
            <a href="#" class="btn btn-danger w-75">🎟️ Đặt vé</a>
        </div>

        <div class="col-md-8">
            <h3 class="fw-bold text-dark">{{ $movies->title }}</h3>
            <p>🎭 <strong>Thể loại:</strong> {{ $movies->genre }}</p>
            <p>⏱️ <strong>Thời lượng:</strong> {{ $movies->duration_min }} phút</p>
            <p>📅 <strong>Khởi chiếu:</strong> {{ \Carbon\Carbon::parse($movies->release_date)->format('d/m/Y') }}</p>

            <h5 class="mt-4 fw-bold">📖 Tóm tắt</h5>
            <p>{{ $movies->description }}</p>
        </div>
    </div>

    {{-- Trailer --}}
    @if ($movies->trailer_url)
        @php
            $trailerEmbed = str_replace('watch?v=', 'embed/', $movies->trailer_url);
            $trailerEmbed = str_replace('youtu.be/', 'www.youtube.com/embed/', $trailerEmbed);
        @endphp
        <div class="mt-5">
            <h5 class="fw-bold mb-3">🎞️ Trailer</h5>
            <div class="text-center">
                <iframe width="70%" height="350"
                        src="{{ $trailerEmbed }}"
                        frameborder="0" allowfullscreen>
                </iframe>
            </div>
        </div>
    @endif

    {{-- Bình luận --}}
    <div class="mt-5">
        <h5 class="fw-bold mb-3">💬 Bình luận</h5>

        {{-- Form gửi bình luận --}}
        <form action="{{ route('comments.store', ['id' => $movies->id]) }}" method="POST" class="mb-4">
            @csrf
            <div class="row mb-2">
                <div class="col-md-4">
                    <input type="text" name="user_name" class="form-control" placeholder="Tên của bạn" required>
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-select" required>
                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                        <option value="4">⭐️⭐️⭐️⭐️</option>
                        <option value="3">⭐️⭐️⭐️</option>
                        <option value="2">⭐️⭐️</option>
                        <option value="1">⭐️</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="content" class="form-control" placeholder="Viết bình luận..." required>
                </div>
            </div>
            <button class="btn btn-primary">Gửi bình luận</button>
        </form>

        {{-- Danh sách bình luận --}}
        @forelse ($comments as $comment)
            <div class="border rounded p-3 mb-2">
                <strong>{{ $comment->user_name }}</strong> 
                <span class="text-warning">{{ str_repeat('⭐', $comment->rating) }}</span>
                <p class="mb-1">{{ $comment->content }}</p>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        @empty
            <p class="text-muted">Chưa có bình luận nào.</p>
        @endforelse
    </div>
</div>
@endsection
