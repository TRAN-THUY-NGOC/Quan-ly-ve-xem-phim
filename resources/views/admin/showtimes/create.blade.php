@extends('layouts.layoutAdmin')

@section('title', 'Thêm suất chiếu mới')

@section('content')
<div style="max-width:850px; margin:30px auto; background:#fff; padding:40px; border-radius:12px;">
    <h2 style="text-align:center; color:#000; font-weight:700; margin-bottom:30px;">THÊM SUẤT CHIẾU MỚI</h2>

    <form action="{{ route('admin.showtimes.store') }}" method="POST">
        @csrf

        {{-- CHỌN PHIM --}}
        <div style="margin-bottom:20px;">
            <label for="film_id" style="font-weight:600;">Chọn phim</label>
            <select id="film_id" name="film_id" class="form-control" required>
                <option value="">-- Chọn phim --</option>
                @foreach ($films as $film)
                    <option value="{{ $film->id }}">{{ $film->title }}</option>
                @endforeach
            </select>
        </div>

        {{-- NGÀY CHIẾU + GIÁ VÉ --}}
        <div style="display:flex; gap:20px; margin-bottom:20px;">
            <div style="flex:1;">
                <label for="date" style="font-weight:600;">Ngày chiếu</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div style="flex:1;">
                <label for="price" style="font-weight:600;">Giá vé (VND)</label>
                <input type="number" id="price" name="price" class="form-control" placeholder="VD: 90000" required>
            </div>
        </div>

        {{-- GIỜ BẮT ĐẦU + GIỜ KẾT THÚC --}}
        <div style="display:flex; gap:20px; margin-bottom:20px;">
            <div style="flex:1;">
                <label for="start_time" style="font-weight:600;">Giờ bắt đầu</label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
            </div>
            <div style="flex:1;">
                <label for="end_time" style="font-weight:600;">Giờ kết thúc</label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
            </div>
        </div>

        {{-- RẠP + PHÒNG --}}
        <div style="display:flex; gap:20px; margin-bottom:20px;">
            <div style="flex:1;">
                <label for="cinema" style="font-weight:600;">Rạp chiếu</label>
                <input type="text" id="cinema" name="cinema" class="form-control" placeholder="VD: CGV Vincom" required>
            </div>
            <div style="flex:1;">
                <label for="room" style="font-weight:600;">Phòng chiếu</label>
                <input type="text" id="room" name="room" class="form-control" placeholder="VD: Phòng 1" required>
            </div>
        </div>

        {{-- TỔNG SỐ GHẾ + GHẾ TRỐNG --}}
        <div style="display:flex; gap:20px; margin-bottom:30px;">
            <div style="flex:1;">
                <label for="total_seats" style="font-weight:600;">Tổng số ghế</label>
                <input type="number" id="total_seats" name="total_seats" class="form-control" placeholder="VD: 120" required>
            </div>
            <div style="flex:1;">
                <label for="available_seats" style="font-weight:600;">Ghế trống</label>
                <input type="number" id="available_seats" name="available_seats" class="form-control" placeholder="VD: 50" required>
            </div>
        </div>

        {{-- NÚT LƯU --}}
        <div style="text-align:center;">
            <button type="submit" style="background-color:#d82323; color:#fff; border:none; padding:10px 25px; border-radius:6px; font-weight:600;">
                + Lưu suất chiếu
            </button>
            <a href="{{ route('admin.showtimes.index') }}" style="margin-left:15px; color:#555;">Hủy</a>
        </div>
    </form>
</div>
@endsection
