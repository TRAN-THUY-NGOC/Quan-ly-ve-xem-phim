@extends('layouts.guest')
@section('title', 'Thanh toán')

@section('content')
<div class="payment-box">
    <table>
        <tr>
            <td>Phim</td>
            <td><b>{{ $order->tickets->first()->showtime->film->name }}</b></td>
        </tr>
        <tr>
            <td>Suất chiếu</td>
            <td>{{ $order->tickets->first()->showtime->date }} - {{ $order->tickets->first()->showtime->time }}</td>
        </tr>
        <tr>
            <td>Rạp</td>
            <td>{{ $order->tickets->first()->showtime->cinema->name }}</td>
        </tr>
        <tr>
            <td>Phòng chiếu</td>
            <td>{{ $order->tickets->first()->showtime->room->name }}</td>
        </tr>
        <tr>
            <td>Ghế</td>
            <td>
                @foreach ($order->tickets as $ticket)
                    {{ $ticket->seat->name }}@if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Giá vé</td>
            <td>{{ number_format($order->tickets->first()->price, 0, ',', '.') }}đ</td>
        </tr>
        <tr>
            <td>Người đặt</td>
            <td>{{ $order->user->name }}</td>
        </tr>
        <tr>
            <td>Số điện thoại</td>
            <td>{{ $order->user->phone }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $order->user->email }}</td>
        </tr>
        <tr>
            <td>Tạm tính</td>
            <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
        </tr>
        <tr>
            <td>Ưu đãi</td>
            <td><a href="#" style="color: blue;">Chọn hoặc nhập mã ></a></td>
        </tr>
        <tr>
            <td><b>Tổng tiền</b></td>
            <td><b>{{ number_format($order->total, 0, ',', '.') }}đ</b></td>
        </tr>
    </table>
</div>
@endsection