@extends('layouts.app')
@section('title','Quản lý tài khoản')

@section('content')
<div class="container">
  <h4 class="mb-3">Quản lý tài khoản</h4>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <form class="row g-2 mb-3">
    <div class="col-auto">
      <input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm tên hoặc email...">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Tìm</button>
      <a href="{{ route('admin.users.create') }}" class="btn btn-success">Thêm tài khoản</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead><tr>
        <th>#</th><th>Tên</th><th>Email</th><th>Điện thoại</th><th>Role</th><th>Hành động</th>
      </tr></thead>
      <tbody>
      @forelse($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->phone }}</td>
          <td>{{ $u->role_id }}</td>
          <td class="d-flex gap-1">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit',$u) }}">Sửa</a>
            <form method="post" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Xoá tài khoản?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Xoá</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-muted">Chưa có tài khoản.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $users->links() }}
</div>
@endsection
