@extends('layouts.app')
@section('title', $user->exists ? 'Sửa tài khoản' : 'Thêm tài khoản')

@section('content')
<div class="container" style="max-width:720px">
  <h4 class="mb-3">{{ $user->exists ? 'Sửa' : 'Thêm' }} tài khoản</h4>

  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul></div>
  @endif

  <form method="post" action="{{ $user->exists ? route('admin.users.update',$user) : route('admin.users.store') }}">
    @csrf
    @if($user->exists) @method('PUT') @endif

    <div class="mb-2">
      <label class="form-label">Họ tên</label>
      <input name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
    </div>
    <div class="mb-2">
      <label class="form-label">Email</label>
      <input name="email" type="email" class="form-control" value="{{ old('email',$user->email) }}" required>
    </div>
    <div class="mb-2">
      <label class="form-label">Điện thoại</label>
      <input name="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
    </div>
    <div class="mb-2">
      <label class="form-label">Vai trò (role_id)</label>
      <select name="role_id" class="form-select">
        <option value="1" @selected(old('role_id',$user->role_id)==1)>Admin</option>
        <option value="2" @selected(old('role_id',$user->role_id)==2)>Customer</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Mật khẩu {{ $user->exists ? '(để trống nếu giữ nguyên)' : '' }}</label>
      <input name="password" type="password" class="form-control" {{ $user->exists ? '' : 'required' }}>
    </div>

    <button class="btn btn-primary">Lưu</button>
    <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">Huỷ</a>
  </form>
</div>
@endsection
