<table class="table align-middle">
  <thead>
    <tr>
      <th>ID</th>
      <th>Tên</th>
      <th>Email</th>
      <th>Vai trò</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th class="text-end">Thao tác</th>
    </tr>
  </thead>
  <tbody>
    @forelse($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>

        {{-- Vai trò: nếu có quan hệ $u->role --}}
        <td>
          @php
            $roleName = optional($u->role)->name ?? ($u->role_name ?? 'Customer');
          @endphp

          @if (Str::lower($roleName) === 'admin')
            <span class="role-badge role-badge--admin">{{ $roleName }}</span>
          @else
            <span class="role-badge">{{ $roleName }}</span>
          @endif
        </td>

        <td>
          {{-- Tuỳ schema: đang hoạt động hay khoá? --}}
          <span class="text-success fw-semibold">Hoạt động</span>
        </td>

        <td>{{ optional($u->created_at)->format('d/m/Y H:i') }}</td>

        <td class="text-end d-flex gap-2 justify-content-end">
          <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Sửa</a>

          <form action="{{ route('admin.users.resetPassword', $u) }}" method="post" 
                onsubmit="return confirm('Đặt lại mật khẩu cho {{ $u->name }}?\n- Nếu có ngày sinh: ddmmyyyy\n- Nếu không: mật khẩu tạm 8 ký tự');">
            @csrf
            <button class="btn btn-sm btn-warning">Reset mật khẩu</button>
          </form>

          <form action="{{ route('admin.users.destroy', $u) }}" method="post"
                onsubmit="return confirm('Xoá tài khoản {{ $u->name }}?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Xoá</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="text-center text-muted">Chưa có tài khoản.</td></tr>
    @endforelse
  </tbody>
</table>

@if (session('ok'))
  <div class="alert alert-success mt-3">{{ session('ok') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
@endif
