@extends('layouts.guest')

@section('title', 'Thanh toán thành công')

@section('content')
<div style="text-align:center; padding:50px 20px;">
    <h2 style="color:#4CAF50; font-size:28px;">🎉 Thanh toán thành công!</h2>
    <p style="margin-top:15px; font-size:18px; color:#333;">
        Cảm ơn bạn đã đặt vé tại hệ thống của chúng tôi.
    </p>
    <p style="margin-top:10px; font-size:16px; color:#666;">
        Chúng tôi đã gửi thông tin vé điện tử đến email của bạn.
    </p>

    <a href="{{ route('datve') }}" 
       style="display:inline-block; margin-top:25px; background-color:#b89053; color:white; 
              padding:10px 25px; border-radius:8px; text-decoration:none; font-weight:bold;">
        🎟️ Đặt thêm vé mới
    </a>
</div>
@endsection
