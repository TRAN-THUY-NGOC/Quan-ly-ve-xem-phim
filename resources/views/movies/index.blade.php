@extends('layouts.app')
@section('title','Phim')

@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-bold">Danh sách phim</h4>
    <form method="get" class="d-flex gap-2">
      <input type="text" class="form-control form-control-lg" name="q" placeholder="Tìm tên phim…" value="{{ $q }}">
      <select name="status" class="form-select form-select-lg" style="max-width:200px">
        <option value="">-- Tất cả --</option>
        <option value="now" {{ $status==='now'?'selected':'' }}>Đang chiếu</option>
        <option value="upcoming" {{ $status==='upcoming'?'selected':'' }}>Sắp chiếu</option>
      </select>
      <button class="btn btn-primary btn-lg"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="row g-3">
    @forelse($movies as $m)
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
          <img src="{{ $m->poster_url ?? asset('images/no-poster.png') }}" class="card-img-top" alt="{{ $m->title }}">
          <div class="card-body d-flex flex-column">
            <h6 class="fw-bold mb-1">{{ $m->title }}</h6>
            <div class="text-muted small mb-2">
              Khởi chiếu: {{ \Illuminate\Support\Carbon::parse($m->release_date)->format('d/m/Y') }}
            </div>
            <a href="{{ route('movies.show',$m->id) }}" class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-4">Không có phim nào.</div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $movies->links() }}
  </div>
</div>
@endsection
