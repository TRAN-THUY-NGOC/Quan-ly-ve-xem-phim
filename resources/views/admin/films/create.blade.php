@extends('layouts.layoutAdmin')

@section('title', 'Thêm phim mới')

@section('content')
<div style="max-width:700px; margin:30px auto; background:#fff; padding:20px; border-radius:10px;">
    <h2 style="text-align:center; color:#d82323;">THÊM PHIM MỚI</h2>

    <form action="{{ route('films.store') }}" method="POST">
        @csrf

        <label>Tên phim:</label>
        <input type="text" name="title" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Thể loại:</label>
        <input type="text" name="genre" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Thời lượng (phút):</label>
        <input type="number" name="duration_min" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Ngày khởi chiếu:</label>
        <input type="date" name="release_date" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Mô tả:</label>
        <textarea name="description" rows="3" style="width:100%; padding:8px; margin-bottom:10px;"></textarea>

        <button type="submit" style="background-color:#d82323; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer;">
            💾 Lưu phim
        </button>

        <a href="{{ route('films.index') }}" style="margin-left:10px; text-decoration:none; color:#555;">⬅ Quay lại</a>
    </form>
</div>
@endsection
