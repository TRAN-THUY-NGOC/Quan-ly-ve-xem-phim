@extends('layouts.app')
@section('title', $mode==='create'?'Thêm Suất chiếu':'Sửa Suất chiếu')

@section('page_toolbar')
  <div class="page-toolbar">
    <div class="container d-flex align-items-center justify-content-between gap-2">
      <div class="dropdown">
        <button class="pt-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          Quản trị
        </button>
        <ul class="dropdown-menu shadow-sm">
          <li><a class="dropdown-item" href="{{ route('admin.movies.index') }}">Quản lý phim</a></li>
          <li><a class="dropdown-item" href="{{ route('admin.showtimes.index') }}">Quản lý suất chiếu</a></li>
          <li><a class="dropdown-item" href="{{ route('admin.rooms.index') }}">Quản lý phòng chiếu</a></li>
          <li><a class="dropdown-item" href="{{ route('admin.prices.index') }}">Quản lý giá vé</a></li>
          <li><a class="dropdown-item" href="{{ route('admin.tickets.index') }}">Quản lý đơn vé</a></li>
          <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Báo cáo & thống kê</a></li>
        </ul>
      </div>

      <div class="page-title text-center flex-grow-1">
        <h5 class="m-0 fw-bold">{{ $mode==='create'?'Thêm Suất chiếu':'Sửa Suất chiếu' }}</h5>
      </div>

      <div class="toolbar-actions">
        <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left"></i> Danh sách
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
<div class="container mt-3">
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST"
            action="{{ $mode==='create'
                      ? route('admin.showtimes.store')
                      : route('admin.showtimes.update',$showtime) }}">
        @csrf
        @if($mode==='edit') @method('PUT') @endif

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Phim</label>
            <select name="movie_id" class="form-select" required>
              <option value="">-- chọn phim --</option>
              @foreach($movies as $m)
                <option value="{{ $m->id }}"
                  @selected(old('movie_id', $showtime->movie_id) == $m->id)>{{ $m->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Phòng chiếu</label>
            <select name="room_id" class="form-select" required>
              <option value="">-- chọn phòng --</option>
              @foreach($rooms as $r)
                <option value="{{ $r->id }}"
                  @selected(old('room_id', $showtime->room_id) == $r->id)>{{ $r->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Bắt đầu</label>
            <input type="datetime-local" name="start_time" class="form-control"
                   value="{{ old('start_time',
                        $showtime->start_time ? $showtime->start_time->format('Y-m-d\TH:i') : '') }}"
                   required>
            <div class="form-text">Kết thúc sẽ tự tính = bắt đầu + thời lượng phim.</div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu
          </button>
          <a href="{{ route('admin.showtimes.index') }}" class="btn btn-light">Huỷ</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
