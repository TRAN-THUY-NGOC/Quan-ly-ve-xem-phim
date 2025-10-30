@extends('layouts.layoutAdmin')

@section('title', 'Thống kê ghế theo phòng')

@section('content')
<div style="max-width:1000px; margin:0 auto; background:#fff; padding:25px; border-radius:10px;">
    <h2 style="text-align:center; color:#333;">THỐNG KÊ GHẾ THEO PHÒNG</h2>

    <table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse; text-align:center;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>Phòng</th>
                <th>Loại ghế</th>
                <th>Tổng ghế</th>
                <th>Trống</th>
                <th>Đã đặt</th>
                <th>Hỏng</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->room_name }}</td>
                    <td>{{ $row->seat_type }}</td>
                    <td>{{ $row->total }}</td>
                    <td style="color:#27ae60; font-weight:bold;">{{ $row->available_count }}</td>
                    <td style="color:#e67e22; font-weight:bold;">{{ $row->booked_count }}</td>
                    <td style="color:#e74c3c; font-weight:bold;">{{ $row->broken_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="color:#888;">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
