@extends('layouts.admin')
@section('title','Thêm phim')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">THÊM PHIM MỚI</h2>
    <form action="{{ route('admin.phim.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Tên phim</label>
            <input type="text" name="tenphim" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Thể loại</label>
            <input type="text" name="theloai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Thời lượng (phút)</label>
            <input type="number" name="thoiluong" class="form-control">
        </div>
        <div class="mb-3">
            <label>Ngày khởi chiếu</label>
            <input type="date" name="ngaychieu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá vé (VND)</label>
            <input type="number" name="gia" class="form-control">
        </div>
        <div class="mb-3">
            <label>Poster</label>
            <input type="file" name="poster" class="form-control">
        </div>
        <div class="mb-3">
            <label>Trailer</label>
            <input type="file" name="trailer" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Lưu phim</button>
    </form>
</div>
@endsection
