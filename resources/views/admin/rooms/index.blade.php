@extends('layouts.app')
@section('title','Quản lý Phòng chiếu')

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
        <h5 class="m-0 fw-bold">Quản lý Phòng chiếu</h5>
      </div>

      <div class="toolbar-actions">
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-circle"></i> Thêm phòng
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
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Tên phòng</th>
              <th>Sức chứa</th>
              <th>Số ghế hiện có</th>
              <th width="240">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rooms as $r)
              <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->name }}</td>
                <td>{{ $r->capacity }}</td>
                <td>{{ $r->seats_count ?? 0 }}</td>
                <td>
                  <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.rooms.edit',$r) }}">
                    <i class="bi bi-pencil-square"></i> Sửa
                  </a>

                  <form method="POST" action="{{ route('admin.rooms.destroy',$r) }}" class="d-inline"
                        onsubmit="return confirm('Xoá phòng {{ $r->name }}?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash"></i> Xoá
                    </button>
                  </form>

                  <form method="POST" action="{{ route('admin.rooms.generateSeats',$r) }}" class="d-inline"
                        onsubmit="return confirm('Tạo lại ghế cho phòng {{ $r->name }}? Ghế cũ (nếu có) sẽ bị thay thế.');">
                    @csrf
                    <button class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-grid-3x3-gap"></i> Tạo ghế
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có phòng nào.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer bg-white">
      {{ $rooms->links() }}
    </div>
  </div>
</div>
@endsection
