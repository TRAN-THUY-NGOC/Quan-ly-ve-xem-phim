@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-3">Bảng điều khiển Admin</h2>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="row g-3">
    <div class="col-md-4">
      <a class="btn btn-primary w-100" href="{{ route('admin.movies.index') }}">🎬 Quản lý phim</a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-primary w-100" href="{{ route('admin.rooms.index') }}">🏢 Quản lý phòng chiếu</a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-primary w-100" href="{{ route('admin.showtimes.index') }}">⏰ Quản lý suất chiếu</a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-outline-primary w-100" href="{{ route('admin.prices.index') }}">💺 Cấu hình giá</a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-outline-primary w-100" href="{{ route('admin.tickets.index') }}">🎟️ Đơn vé</a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-outline-primary w-100" href="{{ route('admin.reports.index') }}">📊 Báo cáo</a>
    </div>
  </div>
</div>
@endsection
