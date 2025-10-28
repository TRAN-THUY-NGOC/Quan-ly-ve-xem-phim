@extends('layouts.layoutAdmin')
@section('title', 'Cập nhật thông tin quản trị viên')

@section('content')
<style>
    body {
        background-color: #f8f5ef;
        font-family: 'Arial', sans-serif;
    }
    .update-container {
        max-width: 700px;
        margin: 40px auto;
        background: #fffaf0;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    label {
        font-weight: bold;
        color: #333;
    }
    input[type="text"], input[type="date"], input[type="email"], input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }
    button {
        background-color: #d62d20;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
    }
    button:hover {
        background-color: #b31d14;
    }
</style>

<div class="update-container">
    <h3 class="text-center mb-4">CẬP NHẬT THÔNG TIN</h3>

    <form action="{{ route('admin.updateInfo') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Ảnh đại diện</label><br>
            @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                        width="120" height="120" 
                        class="rounded-circle mb-2">
            @endif
            <input type="file" name="avatar">
        </div>

        <div class="mb-3">
            <label>Họ và tên</label>
            <input type="text" name="name" value="{{ Auth::user()->name }}">
        </div>

        <div class="mb-3">
            <label>Ngày sinh</label>
            <input type="date" name="birthday" value="{{ Auth::user()->birthday }}">
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" value="{{ Auth::user()->phone }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ Auth::user()->email }}">
        </div>

        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" value="{{ Auth::user()->address }}">
        </div>

        <div class="text-center">
            <button type="submit">Lưu thay đổi</button>
        </div>
    </form>
</div>
@endsection
