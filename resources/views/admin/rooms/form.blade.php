@extends('layouts.app')
@section('title', $mode==='create'?'Thêm Phòng chiếu':'Sửa Phòng chiếu')

@section('page_toolbar')
  <div class="page-toolbar">
    <div class="container d-flex align-items-center justify-content-between gap-2">

      <div class="dropdown">
        <button class="pt-btn dropdown-toggle" data-bs-toggle="dropdown">Quản trị</button>
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
        <h5 class="m-0 fw-bold">{{ $mode==='create'?'Thêm Phòng chiếu':'Sửa Phòng chiếu' }}</h5>
      </div>

      <div class="toolbar-actions">
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm">
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
                      ? route('admin.rooms.store')
                      : route('admin.rooms.update',$room) }}">
        @csrf
        @if($mode==='edit') @method('PUT') @endif

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Tên phòng</label>
            <input type="text" name="name" class="form-control" required
                   value="{{ old('name', $room->name) }}" placeholder="VD: R1, VIP A">
          </div>

          <div class="col-md-6">
            <label class="form-label">Sức chứa</label>
            <input type="number" name="capacity" class="form-control" required min="1" max="1000"
                   value="{{ old('capacity', $room->capacity) }}" placeholder="VD: 40">
            <div class="form-text">Nút “Tạo ghế” sẽ sinh số ghế tối đa theo sức chứa này.</div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu
          </button>
          <a href="{{ route('admin.rooms.index') }}" class="btn btn-light">Huỷ</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
