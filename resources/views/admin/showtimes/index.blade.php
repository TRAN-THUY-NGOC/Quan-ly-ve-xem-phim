@extends('layouts.layoutAdmin')

@section('title', 'Quản lý suất chiếu')

@section('content')
<div style="max-width:1100px; margin:30px auto; background:#fff; padding:30px; border-radius:12px;">
    <h2 style="text-align:center; color:#d82323; font-weight:700; margin-bottom:25px;">🎞️ QUẢN LÝ SUẤT CHIẾU</h2>

    {{-- Thanh tìm kiếm --}}
    <form action="{{ route('admin.showtimes.index') }}" method="GET" style="display:flex; gap:10px; margin-bottom:25px; flex-wrap:wrap;">
        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
               placeholder="Tìm phim, rạp hoặc phòng..." style="flex:2; min-width:200px;">
        <select name="status" class="form-control" style="flex:1; min-width:150px;">
            <option value="">-- Trạng thái --</option>
            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang chiếu</option>
            <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã chiếu</option>
        </select>
        <button type="submit" style="background:#d82323; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:600;">
            🔍 Tìm kiếm
        </button>
        <a href="{{ route('admin.showtimes.create') }}" 
           style="background:#28a745; color:#fff; padding:8px 18px; border-radius:6px; text-decoration:none; font-weight:600;">
           + Thêm suất chiếu
        </a>
    </form>

    {{-- Bảng danh sách --}}
    <table class="table table-bordered table-striped" style="width:100%; border-radius:10px; overflow:hidden;">
        <thead style="background:#f4f4f4; text-align:center;">
            <tr>
                <th>ID</th>
                <th>Phim</th>
                <th>Rạp</th>
                <th>Phòng</th>
                <th>Ngày chiếu</th>
                <th>Giờ</th>
                <th>Giá vé</th>
                <th>Ghế</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($showtimes as $st)
                <tr style="text-align:center;">
                    <td>{{ $st->id }}</td>
                    <td>{{ $st->film->title ?? '—' }}</td>
                    <td>{{ $st->cinema }}</td>
                    <td>{{ $st->room }}</td>
                    <td>{{ \Carbon\Carbon::parse($st->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($st->end_time)->format('H:i') }}</td>
                    <td>{{ number_format($st->price, 0, ',', '.') }}đ</td>
                    <td>{{ $st->available_seats }}/{{ $st->total_seats }}</td>
                    <td>
                        @php
                            $color = match($st->status) {
                                'upcoming' => '#17a2b8',
                                'active' => '#28a745',
                                'ended' => '#6c757d',
                                default => '#999'
                            };
                        @endphp
                        <span style="background:{{ $color }}; color:#fff; padding:4px 10px; border-radius:6px;">
                            {{ ucfirst($st->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.showtimes.show', $st->id) }}" style="color:#007bff; font-weight:600;">Xem</a> |
                        <a href="{{ route('admin.showtimes.edit', $st->id) }}" style="color:#28a745; font-weight:600;">Sửa</a> |
                        <form action="{{ route('admin.showtimes.destroy', $st->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Xóa suất chiếu này?')" 
                                    style="color:#d82323; background:none; border:none; font-weight:600; cursor:pointer;">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" style="text-align:center; color:#777;">Không có suất chiếu nào.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $showtimes->links() }}
    </div>
</div>
@endsection
