<<<<<<< Updated upstream
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
=======
@extends('layouts.guest')
@section('title', 'Đăng ký')

@section('content')
<style>
    body {
        background-color: #f9f6ef;
        font-family: Arial, Helvetica, sans-serif;
    }

    .section-title {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        color: #3b2a19;
        text-transform: uppercase;
        margin-bottom: 25px;
        margin-top: 15px;
    }

    /* --- Thanh trên cùng (nếu có) --- */
    .navbar-top {
        background-color: #f7f1e7;
        padding: 6px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }

    .navbar-top a {
        color: #000;
        text-decoration: none;
        margin: 0 10px;
    }

    .navbar-top a:hover {
        text-decoration: underline;
    }

    .nav-left a {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .nav-left img {
        height: 15px;
    }

    .lang-btn {
        border: none;
        background: none;
        cursor: pointer;
        font-size: 13px;
        color: #000;
        font-weight: 500;
    }

    /* --- Khung đăng ký (màu be nhạt) --- */
    .register-card {
        background-color: #e9d3a3;
        border-radius: 12px;
        padding: 35px 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .register-card label {
        font-weight: bold;
        font-size: 16px;
        color: #222;
        display: block;
        margin-bottom: 6px;
    }

    .register-card input[type="text"],
    .register-card input[type="email"],
    .register-card input[type="tel"],
    .register-card input[type="password"] {
        width: 100%;
        background-color: #f2efe5;
        border: none;
        border-radius: 4px;
        padding: 9px 11px;
        margin-bottom: 18px; /* 👈 thêm khoảng cách giữa các dòng */
        font-size: 15px;
        outline: none;
    }

    .register-card input:focus {
        background-color: #f8f6ef;
        box-shadow: 0 0 0 2px #d82323 inset;
    }

    /* --- Nút đăng ký --- */
    .btn-danger {
        background-color: #d82323;
        border: none;
        padding: 10px 25px;
        color: #fff;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-danger:hover {
        background-color: #b81919;
    }

    /* --- Responsive --- */
    @media (max-width: 768px) {
        .navbar-top {
            flex-direction: column;
            text-align: center;
        }

        .register-card {
            padding: 25px;
        }
    }
</style>

<!-- ====== TIÊU ĐỀ ====== -->
<div class="section-title">TẠO TÀI KHOẢN MỚI</div>

<!-- ====== FORM ====== -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
            <div class="register-card">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name">Họ và tên:</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email:</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone">Số điện thoại:</label>
                        <input id="phone" type="tel" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="password">Mật khẩu:</label>
                        <input id="password" type="password" name="password" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation">Xác nhận mật khẩu:</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger px-5">Đăng ký</button>
                    </div>

                    <p class="text-center mt-3 mb-0">
                        Đã có tài khoản?
                        <a href="{{ route('login') }}" class="fw-bold link-dark">Đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
