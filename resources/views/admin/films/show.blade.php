@extends('layouts.layoutAdmin')

@section('title', 'Chi tiết phim')

@section('content')
<div style="max-width:900px; margin:30px auto; background:#fff; padding:25px 35px; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center; color:#d82323; margin-bottom:25px;">THÔNG TIN CHI TIẾT PHIM</h2>

    <div style="display:flex; flex-wrap:wrap; gap:25px;">
        {{-- Poster --}}
        <div style="flex:1; min-width:250px; text-align:center;">
            <img src="{{ $film->poster_url ? $film->poster_url : 'https://via.placeholder.com/250x360?text=No+Poster' }}" 
                 alt="Poster" style="width:100%; max-width:250px; height:auto; border-radius:10px;">
        </div>

        {{-- Thông tin --}}
        <div style="flex:2; min-width:250px;">
            <p><b>Mã phim:</b> {{ 'F' . str_pad($film->id, 3, '0', STR_PAD_LEFT) }}</p>
            <p><b>Tên phim:</b> {{ $film->title }}</p>
            <p><b>Thể loại:</b> {{ $film->genre ?? '—' }}</p>
            <p><b>Thời lượng:</b> {{ $film->duration_min ?? '—' }} phút</p>
            <p><b>Ngày khởi chiếu:</b> 
                {{ $film->release_date ? \Carbon\Carbon::parse($film->release_date)->format('d/m/Y') : '—' }}
            </p>
<p><b>Giá vé:</b> {{ number_format($film->price ?? 100000) }} VNĐ</p>

@php
    $statusText = match($film->status) {
        'active' => 'Đang chiếu',
        'upcoming' => 'Sắp chiếu',
        'inactive' => 'Ngừng chiếu',
        default => '⏸ Không xác định',
    };

    $statusColor = match($film->status) {
        'active' => '#27ae60',
        'upcoming' => '#f39c12',
        'inactive' => '#e74c3c',
        default => '#7f8c8d',
    };
@endphp

<p><b>Trạng thái:</b>
    <span style="color:{{ $statusColor }}; font-weight:600;">
        {{ $statusText }}
    </span>
</p>

<p><b>Trailer:</b>
                @if($film->trailer_url)
                    <a href="{{ $film->trailer_url }}" target="_blank">Xem trailer 🎬</a>
                @else
                    Không có
                @endif
            </p>
            <p><b>Mô tả:</b><br>{{ $film->description ?? 'Chưa có mô tả' }}</p>
            <p style="font-size:13px; color:#888; margin-top:15px;">
                <i>Ngày tạo: {{ $film->created_at }}</i><br>
                <i>Cập nhật gần nhất: {{ $film->updated_at }}</i>
            </p>
        </div>
    </div>

    {{-- Nút hành động --}}
    <div style="margin-top:30px; display:flex; justify-content:center; gap:15px;">
        <a href="{{ route('admin.films.edit', $film->id) }}" 
            style="background-color:#27ae60; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
            Chỉnh sửa thông tin
        </a>

        <a href="{{ route('admin.films.index') }}" 
            style="background-color:#95a5a6; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
            ⬅ Quay lại danh sách
        </a>
    </div>
</div>
@endsection
