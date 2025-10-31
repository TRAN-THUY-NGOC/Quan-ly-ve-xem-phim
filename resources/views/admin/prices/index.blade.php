@extends('layouts.app')
@section('title','Quản lý Giá vé')

@section('page_toolbar')
<div class="page-toolbar">
  <div class="container d-flex align-items-center justify-content-between gap-2">
    <div class="dropdown">
      <button class="pt-btn dropdown-toggle" data-bs-toggle="dropdown">Quản trị</button>
      <ul class="dropdown-menu shadow-sm">
        <li><a class="dropdown-item" href="{{ route('admin.movies.index') }}">Quản lý phim</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.showtimes.index') }}">Quản lý suất chiếu</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.rooms.index') }}">Quản lý phòng chiếu</a></li>
        <li><a class="dropdown-item active" href="{{ route('admin.prices.index') }}">Quản lý giá vé</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.tickets.index') }}">Quản lý đơn vé</a></li>
        <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Báo cáo & thống kê</a></li>
      </ul>
    </div>
    <div class="page-title flex-grow-1 text-center"><h5 class="m-0 fw-bold">Quản lý Giá vé</h5></div>
    <div></div>
  </div>
</div>
@endsection

@section('content')
<div class="container mt-3">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  {{-- Filter --}}
  <form class="card card-body shadow-sm mb-3" method="get">
    <div class="row g-2">
      <div class="col-md-4">
        <label class="form-label">Phim</label>
        <select name="movie_id" class="form-select">
          <option value="">-- Tất cả --</option>
          @foreach($movies as $m)
            <option value="{{ $m->id }}" @selected($movieId==$m->id)>{{ $m->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Phòng</label>
        <select name="room_id" class="form-select">
          <option value="">-- Tất cả --</option>
          @foreach($rooms as $rm)
            <option value="{{ $rm->id }}" @selected($roomId==$rm->id)>{{ $rm->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Ngày chiếu</label>
        <input type="date" name="date" value="{{ $date }}" class="form-control">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-secondary w-100">Lọc</button>
      </div>
    </div>
  </form>

  {{-- Editable table --}}
  <form method="post" action="{{ route('admin.prices.store') }}" class="card shadow-sm">
    @csrf
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Suất chiếu</th>
            <th>Phòng</th>
            <th>Phim</th>
            <th>Loại ghế</th>
            <th>Giá gốc</th>
            <th>Điều chỉnh</th>
            <th>Giá cuối</th>
          </tr>
        </thead>
        <tbody>
        @forelse($prices as $p)
          @php
            $final = ($p->base_price ?? 0) + ($p->price_modifier ?? 0);
          @endphp
          <tr>
            <td>
              <div class="small text-muted">{{ \Carbon\Carbon::parse($p->start_time)->format('d/m/Y H:i') }}</div>
              <div class="small">→ {{ \Carbon\Carbon::parse($p->end_time)->format('H:i') }}</div>
            </td>
            <td>{{ $p->room_name }}</td>
            <td class="fw-semibold">{{ $p->movie_title }}</td>
            <td>{{ $p->seat_type_name }}</td>
            <td>{{ number_format($p->base_price ?? 0) }} đ</td>
            <td style="max-width:160px;">
              <input type="hidden" name="price[{{ $loop->index }}][id]" value="{{ $p->id }}">
              <input type="number" step="1000" name="price[{{ $loop->index }}][price_modifier]" value="{{ $p->price_modifier }}"
                     class="form-control form-control-sm">
            </td>
            <td class="fw-bold">{{ number_format($final) }} đ</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có cấu hình giá.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center bg-white">
      {{ $prices->links() }}
      <button class="btn btn-primary"><i class="bi bi-save"></i> Lưu thay đổi</button>
    </div>
  </form>
</div>
@endsection
