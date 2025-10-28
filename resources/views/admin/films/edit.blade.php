@extends('admin.layoutAdmin')

@section('title', 'Chỉnh sửa phim')

@section('content')
<div style="max-width:700px; margin:30px auto; background:#fff; padding:20px; border-radius:10px;">
    <h2 style="text-align:center; color:#bfa476;">CHỈNH SỬA PHIM</h2>

    <form action="{{ route('films.update', $film->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Tên phim:</label>
        <input type="text" name="title" value="{{ $film->title }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Thể loại:</label>
        <input type="text" name="genre" value="{{ $film->genre }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Thời lượng (phút):</label>
        <input type="number" name="duration_min" value="{{ $film->duration_min }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Ngày khởi chiếu:</label>
        <input type="date" name="release_date" value="{{ $film->release_date }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        <label>Mô tả:</label>
        <textarea name="description" rows="3" style="width:100%; padding:8px; margin-bottom:10px;">{{ $film->description }}</textarea>

        <label>Trạng thái:</label>
        <select name="is_active" style="width:100%; padding:8px; margin-bottom:10px;">
            <option value="1" {{ $film->is_active ? 'selected' : '' }}>Đang chiếu</option>
            <option value="0" {{ !$film->is_active ? 'selected' : '' }}>Sắp chiếu</option>
        </select>

        <button type="submit" style="background-color:#bfa476; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer;">
            💾 Cập nhật
        </button>

        <a href="{{ route('films.index') }}" style="margin-left:10px; text-decoration:none; color:#555;">⬅ Quay lại</a>
    </form>
</div>
@endsection
