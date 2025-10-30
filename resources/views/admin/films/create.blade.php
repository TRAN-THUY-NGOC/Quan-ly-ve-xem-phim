@extends('layouts.layoutAdmin')

@section('title', 'Thêm phim mới')

@section('content')
<div style="
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
">
    <h2 style="
        text-align:center;
        margin-bottom:30px;
        color:#000000ff;
        font-weight:700;
        font-size:24px;
        letter-spacing:1px;
    ">
        THÊM PHIM MỚI
    </h2>

    <form action="{{ route('admin.films.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Mã phim --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Mã phim:</label>
            <input type="text" name="film_code" 
                   class="form-control" 
                   placeholder="Ví dụ: F001"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Tên phim --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Tên phim:</label>
            <input type="text" name="title" required 
                   class="form-control" 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Thể loại --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Thể loại:</label>
            <input type="text" name="genre" required 
                   class="form-control" 
                   placeholder="Ví dụ: Hành động, Tình cảm, Kinh dị..."
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Đạo diễn --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Đạo diễn:</label>
            <input type="text" name="director" 
                   class="form-control"
                   placeholder="Tên đạo diễn"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Diễn viên --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Diễn viên:</label>
            <input type="text" name="cast" 
                   class="form-control"
                   placeholder="Danh sách diễn viên chính"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Quốc gia --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Quốc gia:</label>
            <input type="text" name="country" 
                   class="form-control"
                   placeholder="Ví dụ: Việt Nam, Mỹ, Hàn Quốc..."
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Ngôn ngữ --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Ngôn ngữ:</label>
            <input type="text" name="language"
                   class="form-control"
                   placeholder="Ví dụ: Tiếng Việt, English..."
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Thời lượng --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Thời lượng (phút):</label>
            <input type="number" name="duration_min" required min="1"
                   class="form-control" 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Ngày khởi chiếu --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Ngày khởi chiếu:</label>
            <input type="date" name="release_date" required 
                   class="form-control" 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Giá vé --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Giá vé (VNĐ):</label>
            <input type="number" name="ticket_price" min="0"
                   class="form-control"
                   placeholder="Ví dụ: 90000"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Trạng thái --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Trạng thái:</label>
            <select name="status" required
                    style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                <option value="active">Đang chiếu</option>
                <option value="upcoming">Sắp chiếu</option>
                <option value="inactive">Ngừng chiếu</option>
            </select>
        </div>

        {{-- Trailer --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Đường dẫn trailer:</label>
            <input type="text" name="trailer_url" 
                   class="form-control"
                   placeholder="https://youtube.com/..."
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Ảnh Poster --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Ảnh Poster (upload):</label>
            <input type="file" name="poster" accept="image/*"
                   class="form-control"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Ảnh nội bộ --}}
        <div style="margin-bottom:15px;">
            <label style="font-weight:600; color:#34495e;">Đường dẫn ảnh nội bộ:</label>
            <input type="text" name="image"
                   class="form-control"
                   placeholder="/uploads/films/image.jpg"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        {{-- Hoạt động --}}
        <div style="margin-bottom:25px;">
            <label style="font-weight:600; color:#34495e;">Hoạt động:</label>
            <select name="is_active" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                <option value="1">Có</option>
                <option value="0">Không</option>
            </select>
        </div>

        {{-- Mô tả --}}
        <div style="margin-bottom:25px;">
            <label style="font-weight:600; color:#34495e;">Mô tả chi tiết:</label>
            <textarea name="description" rows="4"
                      style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; resize:none;"
                      placeholder="Tóm tắt nội dung, thông tin phim..."></textarea>
        </div>

        {{-- Nút hành động --}}
        <div style="display:flex; justify-content:center; gap:15px; margin-top:25px;">
            <button type="submit"
                style="background-color:#d82323; color:white; border:none; padding:10px 25px; border-radius:6px; font-weight:600; cursor:pointer;">
                💾 Lưu phim
            </button>

            <a href="{{ route('admin.films.index') }}"
                style="background-color:#7f8c8d; color:white; padding:10px 25px; border-radius:6px; font-weight:600; text-decoration:none;">
                ⬅ Quay lại
            </a>
        </div>
    </form>
</div>

<style>
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #d82323;
        box-shadow: 0 0 5px rgba(216,35,35,0.4);
    }
</style>
@endsection
