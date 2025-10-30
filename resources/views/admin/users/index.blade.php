@extends('layouts.app')
@section('title','Quản lý tài khoản')

@section('content')
<div class="container-fluid">
  <h4 class="fw-bold mb-3">Quản lý tài khoản người dùng</h4>
  <div class="mb-2">
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
      <i class="bi bi-plus-circle"></i> Thêm tài khoản
    </a>
  </div>
  <table class="table table-sm table-striped">
    <thead>
      <tr>
        <th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th>
        <th>Trạng thái</th><th>Ngày tạo</th><th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td><span class="badge text-bg-info">{{ $u->role }}</span></td>
          <td>
            <span class="badge text-bg-{{ $u->status=='active'?'success':'secondary' }}">{{ $u->status }}</span>
          </td>
          <td>{{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y H:i') }}</td>
          <td>
            <a href="{{ route('admin.users.edit',$u->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
            <form action="{{ route('admin.users.destroy',$u->id) }}" method="post" class="d-inline">
              @csrf @method('delete')
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá tài khoản này?')">Xoá</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $users->links() }}
</div>
@endsection
