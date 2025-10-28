@extends('layouts.layoutAdmin')

@section('title', 'Quản lý phim')

@section('content')
<div style="max-width:1100px; margin:0 auto;">

    <h2 style="text-align:center; margin-bottom:20px; color:#333;">QUẢN LÝ PHIM</h2>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <form method="GET" action="{{ route('admin.films.index') }}" style="display:flex; align-items:center;">
            <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}"
                   style="padding:6px 10px; border:1px solid #ccc; border-radius:5px;">
            <select name="genre" onchange="this.form.submit()"
                    style="margin-left:10px; padding:6px 10px; border-radius:5px;">
                <option value="all">Thể loại</option>
                @php
                    $genres = \App\Models\Film::select('genre')->distinct()->pluck('genre');
                @endphp
                @foreach($genres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                @endforeach
            </select>
            <button type="submit" style="margin-left:10px; background-color:#bfa476; color:white; border:none; padding:6px 10px; border-radius:5px; cursor:pointer;">
                🔍 Tìm kiếm
            </button>
        </form>

        <a href="{{ route('admin.films.create') }}"
           style="background-color:#d82323; color:white; padding:8px 15px; border-radius:5px; text-decoration:none; font-weight:bold;">
            + Thêm phim mới
        </a>
    </div>

    <table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse; background-color:#fff;">
        <thead style="background-color:#efe6d6; text-align:center;">
            <tr>
                <th>STT</th>
                <th>Mã phim</th>
                <th>Tên phim</th>
                <th>Thể loại</th>
                <th>Ngày khởi chiếu</th>
                <th>Trạng thái</th>
                <th>Giá vé (VNĐ)</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($films as $index => $film)
            <tr style="text-align:center;">
                <td>{{ $loop->iteration }}</td>
                <td>P{{ str_pad($film->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $film->title }}</td>
                <td>{{ $film->genre }}</td>
                <td>{{ \Carbon\Carbon::parse($film->release_date)->format('d/m/Y') }}</td>
                <td>{{ $film->is_active ? 'Đang chiếu' : 'Sắp chiếu' }}</td>
                <td>{{ number_format(rand(90000, 120000)) }}</td>
                <td>
                    <a href="{{ route('admin.films.edit', $film->id) }}" style="color:#ff6600; text-decoration:none;">✏️</a>
                    <form action="{{ route('admin.films.destroy', $film->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa phim này?')"
                                style="border:none; background:none; color:red; cursor:pointer;">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:15px; text-align:center;">
        {{ $films->links('pagination::bootstrap-4') }}
    </div>

</div>
@endsection
