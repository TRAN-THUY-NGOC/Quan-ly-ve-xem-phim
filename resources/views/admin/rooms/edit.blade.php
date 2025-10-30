@extends('layouts.layoutAdmin')

@section('title', 'Chỉnh sửa phòng chiếu')

@section('content')
<div style="max-width:700px; margin:30px auto; background:#fff; padding:25px; border-radius:10px;">
    <h2 style="text-align:center; color:#d82323; margin-bottom:25px;">CHỈNH SỬA PHÒNG CHIẾU</h2>

    @if ($errors->any())
        <div style="background:#ffe6e6; border-left:5px solid #d82323; padding:10px 15px; margin-bottom:20px;">
            <ul style="margin:0; padding-left:15px; color:#b00000;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" style="display:flex; flex-direction:column; gap:15px;">
        @csrf
        @method('PUT')

        <div>
            <label for="code" style="font-weight:bold;">Mã phòng:</label>
            <input type="text" name="code" id="code" value="{{ old('code', $room->code) }}" required
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div>
            <label for="name" style="font-weight:bold;">Tên phòng:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $room->name) }}" required
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div>
            <label for="cinema_id" style="font-weight:bold;">Rạp chiếu:</label>
            <select name="cinema_id" id="cinema_id" required
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach ($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ old('cinema_id', $room->cinema_id) == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="seat_count" style="font-weight:bold;">Số ghế:</label>
            <input type="number" name="seat_count" id="seat_count" value="{{ old('seat_count', $room->seat_count) }}" required min="10"
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
        </div>

        <div>
            <label for="seat_type" style="font-weight:bold;">Loại ghế:</label>
            <select name="seat_type" id="seat_type" required
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Chọn loại ghế --</option>
                <option value="Thường" {{ old('seat_type', $room->seat_type) == 'Thường' ? 'selected' : '' }}>Thường</option>
                <option value="VIP" {{ old('seat_type', $room->seat_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>

        <div>
            <label for="status" style="font-weight:bold;">Trạng thái:</label>
            <select name="status" id="status" required
                style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- Chọn trạng thái --</option>
                <option value="Hoạt động" {{ old('status', $room->status) == 'Hoạt động' ? 'selected' : '' }}>Hoạt động</option>
                <option value="Bảo trì" {{ old('status', $room->status) == 'Bảo trì' ? 'selected' : '' }}>Bảo trì</option>
            </select>
        </div>

        <div style="display:flex; justify-content:center; gap:15px; margin-top:20px;">
            <button type="submit"
                style="background:#27ae60; color:white; padding:10px 25px; border:none; border-radius:6px; font-weight:bold; cursor:pointer;">
                💾 Lưu thay đổi
            </button>
            <a href="{{ route('admin.rooms.index') }}"
                style="background:#ccc; color:black; padding:10px 25px; border-radius:6px; text-decoration:none; font-weight:bold;">
                ↩ Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
