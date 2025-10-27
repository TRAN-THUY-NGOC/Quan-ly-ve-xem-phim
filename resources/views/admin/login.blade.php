@extends('admin.layout')
@section('title', 'Đăng nhập - CINEMA')

@section('content')
<style>
/* ====== TỔNG THỂ ====== */
body { background-color: #f9f6ef; }

.login-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 50px 20px;
}

/* ====== TIÊU ĐỀ ====== */
.login-title {
    font-weight: 700;
    font-size: 26px;
    color: #3b2a12;
    margin-bottom: 20px;
    letter-spacing: 0.5px;
    text-align: center;
}

/* ====== KHUNG FORM ====== */
.login-box {
    background-color: #e9ce93;
    border-radius: 10px;
    width: 100%;
    max-width: 700px;
    padding: 30px 40px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* ====== FORM ====== */
label {
    display: block;
    font-weight: 600;
    color: #2b1b05;
    font-size: 17px;
    margin-bottom: 6px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    background-color: #f3efe3;
    font-size: 15px;
    outline: none;
    margin-bottom: 20px;
    color: #333;
}

input:focus {
    background-color: #fff;
    box-shadow: 0 0 0 2px #d82323 inset;
}

/* ====== GHI NHỚ ====== */
.form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
}

.form-check label {
    font-weight: 600;
    margin: 0;
}

/* ====== NÚT ====== */
.btn-login {
    background-color: #d82323;
    color: #fff;
    border: none;
    padding: 10px 26px;
    font-weight: 700;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    float: right;
    transition: 0.2s;
}

.btn-login:hover {
    background-color: #b61919;
}

.clearfix::after {
    content: "";
    display: block;
    clear: both;
}

/* ====== Responsive nhỏ ====== */
@media (max-width: 600px) {
    .login-box { padding: 25px 25px; }
    .login-title { font-size: 22px; }
}
</style>

<div class="login-wrapper">
    <h2 class="login-title">ĐĂNG NHẬP</h2>

    <div class="login-box">
        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div>
                <label for="email">Tên đăng nhập:</label>
                <input id="email" type="text" name="email" placeholder="Email hoặc số điện thoại" value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <label for="password">Mật khẩu:</label>
                <input id="password" type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ghi nhớ tôi</label>
            </div>

            <div class="clearfix">
                <button type="submit" class="btn-login">Đăng nhập</button>
            </div>
        </form>
    </div>
</div>
@endsection
