@extends('layouts.layoutAdmin')

@section('title', 'Quản lý phim')

@section('content')
<div style="
    max-width: 1300px;
    margin: 40px auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
">
    <h2 style="
        text-align:center;
        margin-bottom:30px;
        color:#2c3e50;
        font-weight:700;
        font-size:26px;
        letter-spacing:1px;
    ">
        QUẢN LÝ PHIM
    </h2>

    {{-- Thanh tìm kiếm và bộ lọc --}}
    <form method="GET" action="{{ route('admin.films.index') }}" style="margin-bottom:25px;" id="searchForm">
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px;">
            {{-- Ô tìm kiếm --}}
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Tìm theo mã, tên phim..." 
                style="flex:2; min-width:220px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;"
                onkeydown="if(event.key === 'Enter'){ event.preventDefault(); document.getElementById('searchForm').submit(); }"
            >

            {{-- Lọc thể loại --}}
            <select name="genre" 
                    style="flex:1; min-width:150px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Thể loại --</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>
                        {{ $genre }}
                    </option>
                @endforeach
            </select>

            {{-- Lọc trạng thái --}}
            <select name="status" 
                    style="flex:1; min-width:150px; height:42px; padding:0 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Trạng thái --</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang chiếu</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngừng chiếu</option>
            </select>

            {{-- Nút tìm kiếm --}}
            <button type="submit"
                style="background-color:#d82323; color:white; height:42px; padding:0 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer;">
                🔍 Tìm kiếm
            </button>

            {{-- Nút thêm phim --}}
            <a href="{{ route('admin.films.create') }}"
                style="background-color:#27ae60; color:white; height:42px; padding:0 20px; border-radius:6px; font-weight:bold; text-decoration:none; display:flex; align-items:center;">
                + Thêm phim
            </a>
        </div>
    </form>

    {{-- Bảng danh sách phim --}}
    <table class="table table-bordered text-center" style="width:100%; border-collapse:collapse;">
        <thead style="background-color:#f7f1e3;">
            <tr>
                <th>STT</th>
                <th>Poster</th>
                <th>Tên phim</th>
                <th>Thể loại</th>
                <th>Trạng thái</th>
                <th>Giá vé (VNĐ)</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($films as $film)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($film->poster)
                        <img src="{{ asset('storage/' . $film->poster) }}" alt="Poster" 
                             style="width:60px; height:85px; object-fit:cover; border-radius:4px;">
                    @else
                        <span style="color:#aaa;">Không</span>
                    @endif
                </td>
                <td>{{ $film->title }}</td>
                <td>{{ $film->genre }}</td>
                <td>
                    <span style="color:{{ $film->status === 'active' ? '#27ae60' : ($film->status === 'upcoming' ? '#f39c12' : '#e74c3c') }}; font-weight:600;">
                        {{ $film->status === 'active' ? 'Đang chiếu' : ($film->status === 'upcoming' ? 'Sắp chiếu' : 'Ngừng chiếu') }}
                    </span>
                </td>
                <td>{{ number_format($film->price ?? 100000) }}</td>
                
                <td style="display:flex; justify-content:center; align-items:center; gap:20px;">
                    {{-- Xem chi tiết --}}
                    <a href="{{ route('admin.films.show', $film->id) }}" 
                    style="color:#27ae60; font-size:20px;" 
                    title="Xem chi tiết">
                        <i data-lucide="edit"></i>
                    </a>

                    {{-- Xóa --}}
                    <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa phim này?')" 
                                style="border:none; background:none; color:#e74c3c; cursor:pointer; font-size:20px;"
                                title="Xóa phim">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="color:#999;">Không tìm thấy phim nào phù hợp</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Phân trang --}}
    <div style="margin-top:20px; text-align:center;">
        {{ $films->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #f7f1e3;
        color: #2c3e50;
        font-weight: 600;
    }
    tbody tr:nth-child(even) {
        background-color: #fafafa;
    }
    tbody tr:hover {
        background-color: #f0f8ff;
    }
    td:last-child {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 18px;
        height: 100%;
    }
    td:nth-child(2) {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    td [data-lucide] {
        transition: transform 0.2s, opacity 0.2s;
    }
    td [data-lucide]:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>
@endsection
