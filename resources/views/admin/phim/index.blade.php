@extends('layouts.admin')
@section('title','Quản lý phim')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">QUẢN LÝ PHIM</h2>
    <a href="{{ route('admin.phim.create') }}" class="btn btn-success mb-3">+ Thêm phim mới</a>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Tên phim</th>
                <th>Thể loại</th>
                <th>Ngày chiếu</th>
                <th>Poster</th>
                <th>Trailer</th>
                <th>Giá (VND)</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($phims as $index => $phim)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $phim->tenphim }}</td>
                <td>{{ $phim->theloai }}</td>
                <td>{{ $phim->ngaychieu }}</td>
                <td>
                    @if($phim->poster)
                        <img src="{{ asset('storage/'.$phim->poster) }}" width="80">
                    @endif
                </td>
                <td>
                    @if($phim->trailer)
                        <a href="{{ asset('storage/'.$phim->trailer) }}" target="_blank">Xem</a>
                    @endif
                </td>
                <td>{{ number_format($phim->gia, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('admin.phim.edit', $phim->id) }}" class="btn btn-warning btn-sm">✏️</a>
                    <form action="{{ route('admin.phim.destroy', $phim->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Xóa phim này?')" class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $phims->links() }}
</div>
@endsection
