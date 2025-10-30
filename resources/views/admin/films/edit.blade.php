@extends('layouts.layoutAdmin')

@section('title', 'Chỉnh sửa phim')

@section('content')
<div style="max-width:800px; margin:30px auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center; color:#d82323; margin-bottom:25px;">✏️ CHỈNH SỬA THÔNG TIN PHIM</h2>

    <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Mã phim --}}
        <label>Mã phim:</label>
        <input type="text" name="film_code" value="{{ $film->film_code }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Tên phim --}}
        <label>Tên phim:</label>
        <input type="text" name="title" value="{{ $film->title }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Thể loại --}}
        <label>Thể loại:</label>
        <input type="text" name="genre" value="{{ $film->genre }}" required class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Đạo diễn --}}
        <label>Đạo diễn:</label>
        <input type="text" name="director" value="{{ $film->director }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Diễn viên --}}
        <label>Diễn viên:</label>
        <input type="text" name="cast" value="{{ $film->cast }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Quốc gia --}}
        <label>Quốc gia:</label>
        <input type="text" name="country" value="{{ $film->country }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Ngôn ngữ --}}
        <label>Ngôn ngữ:</label>
        <input type="text" name="language" value="{{ $film->language }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Thời lượng --}}
        <label>Thời lượng (phút):</label>
        <input type="number" name="duration_min" value="{{ $film->duration_min }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Ngày khởi chiếu --}}
        <label>Ngày khởi chiếu:</label>
        <input type="date" name="release_date" value="{{ $film->release_date }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Giá vé --}}
        <label>Giá vé (VNĐ):</label>
        <input type="number" name="ticket_price" value="{{ $film->ticket_price }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Trạng thái --}}
        <label>Trạng thái:</label>
        <select name="status" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">
            <option value="active" {{ $film->status === 'active' ? 'selected' : '' }}>Đang chiếu</option>
            <option value="upcoming" {{ $film->status === 'upcoming' ? 'selected' : '' }}>Sắp chiếu</option>
            <option value="inactive" {{ $film->status === 'inactive' ? 'selected' : '' }}>Ngừng chiếu</option>
        </select>

        {{-- Đường dẫn trailer --}}
        <label>Đường dẫn trailer:</label>
        <input type="text" name="trailer_url" value="{{ $film->trailer_url }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Upload Poster --}}
        <label>Ảnh Poster (upload mới nếu muốn thay):</label>
        <input type="file" name="poster" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">
        @if($film->poster_url)
            <div style="margin-bottom:10px;">
                <img src="{{ $film->poster_url }}" alt="Poster" style="width:120px; border-radius:6px;">
            </div>
        @endif

        {{-- Ảnh nội bộ --}}
        <label>Đường dẫn ảnh nội bộ (image):</label>
        <input type="text" name="image" value="{{ $film->image }}" class="form-control" style="width:100%; padding:8px; margin-bottom:10px;">

        {{-- Mô tả --}}
        <label>Mô tả:</label>
        <textarea name="description" rows="4" style="width:100%; padding:8px; margin-bottom:10px;">{{ $film->description }}</textarea>

        {{-- Hoạt động --}}
        <label>Hoạt động:</label>
        <select name="is_active" style="width:100%; padding:8px; margin-bottom:15px;">
            <option value="1" {{ $film->is_active ? 'selected' : '' }}>Có</option>
            <option value="0" {{ !$film->is_active ? 'selected' : '' }}>Không</option>
        </select>

        {{-- Nút hành động --}}
        <div style="text-align:center; margin-top:20px;">
            <button type="submit" style="background-color:#27ae60; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer;">
                💾 Cập nhật
            </button>

            <a href="{{ route('admin.films.show', $film->id) }}" 
                style="margin-left:10px; background-color:#95a5a6; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
                ⬅ Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
