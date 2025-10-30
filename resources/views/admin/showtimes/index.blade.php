@extends('layouts.app')
@section('title','Quản lý Suất chiếu')

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
        <h5 class="m-0 fw-bold">Quản lý Suất chiếu</h5>
      </div>

      <div class="toolbar-actions">
        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-circle"></i> Thêm suất chiếu
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
<div class="container mt-3">
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Phim</th>
              <th>Phòng</th>
              <th>Bắt đầu</th>
              <th>Kết thúc</th>
              <th width="140">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse($showtimes as $s)
              <tr>
                <td>{{ $s->id }}</td>
                <td class="fw-semibold">{{ $s->movie->title ?? '—' }}</td>
                <td>{{ $s->room->name ?? '—' }}</td>
                <td>{{ $s->start_time?->format('H:i d/m/Y') }}</td>
                <td>{{ $s->end_time?->format('H:i d/m/Y') }}</td>
                <td>
                  <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.showtimes.edit',$s) }}">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <form class="d-inline" method="POST" action="{{ route('admin.showtimes.destroy',$s) }}"
                        onsubmit="return confirm('Xoá suất chiếu này?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có suất chiếu.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white">
      {{ $showtimes->links() }}
    </div>
  </div>
</div>
@endsection
