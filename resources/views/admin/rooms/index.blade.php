@extends('layouts.layoutAdmin')

@section('title', 'Quản lý phòng chiếu')

@section('content')
<div style="max-width:1100px; margin:0 auto; background:#fff; padding:25px; border-radius:10px;">
    <h2 style="text-align:center; margin-bottom:25px; color:#333; font-weight:bold;">QUẢN LÝ PHÒNG CHIẾU</h2>

    {{-- Thanh tìm kiếm và bộ lọc --}}
    <form method="GET" action="{{ route('admin.rooms.index') }}" style="margin-bottom:25px;">
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px;">
            {{-- Ô tìm kiếm --}}
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="Tìm theo mã, tên phòng hoặc rạp..."
                style="flex:2; min-width:220px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;">

            {{-- Ô chọn rạp --}}
            <select name="cinema_id"
                style="flex:1; min-width:150px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}
                    </option>
                @endforeach
            </select>

            {{-- Ô chọn trạng thái --}}
            <select name="status"
                style="flex:1; min-width:150px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Trạng thái --</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Bảo trì</option>
            </select>

            {{-- Nút tìm kiếm --}}
            <button type="submit"
                style="background-color:#d82323; color:white; height:42px; padding:0 20px; border:none; border-radius:6px; font-weight:bold;">
                🔍 Tìm kiếm
            </button>

            {{-- Nút thêm phòng mới --}}
            <a href="{{ route('admin.rooms.create') }}"
                style="background-color:#27ae60; color:white; height:42px; padding:0 20px; border-radius:6px; font-weight:bold; text-decoration:none;">
                + Thêm phòng chiếu mới
            </a>
        </div>
    </form>

    {{-- Bảng danh sách phòng --}}
    <table border="1" cellspacing="0" cellpadding="8" 
           style="width:100%; border-collapse:collapse; background-color:#fff;">
        <thead style="background-color:#f7f1e3; text-align:center;">
            <tr>
                <th>Mã phòng</th>
                <th>Tên phòng</th>
                <th>Rạp chiếu</th>
                <th>Số ghế</th>
                <th>Loại ghế</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                            <tr style="text-align:center;">
                <td>{{ $room->code }}</td>
                <td>{{ $room->name }}</td>
                <td>{{ $room->cinema->name ?? 'Không có dữ liệu' }}</td>
                <td>{{ $room->seat_count }}</td>
                <td>{{ $room->seat_type }}</td>
                <td>
                    <span style="color:{{ $room->status == 'Hoạt động' ? '#27ae60' : '#e67e22' }}; font-weight:600;">
                        {{ $room->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.rooms.edit', $room->id) }}" style="color:#ff6600; text-decoration:none;">✏️</a>
                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa phòng này?')" 
                                style="border:none; background:none; color:red; cursor:pointer;">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#999;">Không tìm thấy phòng chiếu nào phù hợp</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div style="margin-top:15px; text-align:center;">
        {{ $rooms->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các liên kết trong thanh phân trang
    const paginationLinks = document.querySelectorAll('.pagination-list a');

    paginationLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Khi bấm vào, làm mờ nhẹ toàn trang
            document.body.style.opacity = '0.4';
            document.body.style.transition = 'opacity 0.3s ease';
        });
    });
});
</script>

@endsection
