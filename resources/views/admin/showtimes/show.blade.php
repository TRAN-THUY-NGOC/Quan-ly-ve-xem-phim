@extends('layouts.layoutAdmin')

@section('title', 'Chi tiết suất chiếu')

@section('content')
<div style="max-width:900px; margin:30px auto; background:#fff; padding:30px; border-radius:12px;">
    <h2 style="text-align:center; color:#000; font-weight:700; margin-bottom:25px;">
        CHI TIẾT SUẤT CHIẾU
    </h2>

    {{-- Khối thông tin chính --}}
    <div style="display:flex; gap:30px; align-items:flex-start; flex-wrap:wrap;">

        {{-- Ảnh phim --}}
        <div style="flex:1; min-width:250px; text-align:center;">
            @if(isset($showtime->film->image) && $showtime->film->image)
                <img src="{{ asset('storage/' . $showtime->film->image) }}" 
                     alt="{{ $showtime->film->title }}" 
                     style="width:100%; max-width:280px; border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.15);">
            @else
                <div style="width:100%; max-width:280px; height:400px; background:#f0f0f0; border-radius:10px; 
                            display:flex; align-items:center; justify-content:center; color:#888; font-style:italic;">
                    Không có ảnh
                </div>
            @endif
        </div>

        {{-- Thông tin chi tiết --}}
        <div style="flex:2; min-width:280px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <p><strong>Mã suất chiếu:</strong> {{ $showtime->id }}</p>
                    <p><strong>Tên phim:</strong> {{ $showtime->film->title ?? 'Không xác định' }}</p>
                    <p><strong>Rạp:</strong> {{ $showtime->cinema }}</p>
                    <p><strong>Phòng chiếu:</strong> {{ $showtime->room }}</p>
                </div>
                <div>
                    <p><strong>Ngày chiếu:</strong> {{ \Carbon\Carbon::parse($showtime->date)->format('d/m/Y') }}</p>
                    <p><strong>Giờ chiếu:</strong> 
                        {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($showtime->end_time)->format('H:i') }}
                    </p>
                    <p><strong>Giá vé:</strong> {{ number_format($showtime->price, 0, ',', '.') }} VND</p>
                    <p><strong>Ghế trống:</strong> {{ $showtime->available_seats }}/{{ $showtime->total_seats }}</p>
                </div>
            </div>

            {{-- Trạng thái --}}
            @php
                $status = \Carbon\Carbon::parse($showtime->date)->isFuture() ? 'Sắp chiếu' : 'Đã chiếu';
                $statusColor = $status === 'Sắp chiếu' ? '#28a745' : '#6c757d';
            @endphp
            <div style="text-align:center; margin-top:25px;">
                <span style="background:{{ $statusColor }}; color:#fff; padding:8px 15px; border-radius:6px; font-weight:600;">
                    {{ $status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Nút hành động --}}
    <div style="text-align:center; margin-top:35px;">
        <a href="{{ route('admin.showtimes.index') }}" 
           style="background:#6c757d; color:#fff; padding:8px 18px; border-radius:6px; text-decoration:none; margin-right:10px;">
           ← Quay lại
        </a>

        <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" 
           style="background:#28a745; color:#fff; padding:8px 18px; border-radius:6px; text-decoration:none; margin-right:10px;">
           ✏️ Chỉnh sửa
        </a>

        <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Bạn có chắc muốn xóa suất chiếu này không?')"
                    style="background:#d82323; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:600;">
                🗑️ Xóa
            </button>
        </form>
    </div>
</div>
@endsection
