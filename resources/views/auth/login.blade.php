<<<<<<< Updated upstream
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
=======
@extends('layouts.guest')
@section('title', 'Đăng nhập')

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
    color: #3b2a19; /* nâu đậm giống THANH TOÁN */
    text-transform: uppercase;
    margin-bottom: 25px;
    margin-top: 15px;
}

    .login-card {
        background-color: #e9d3a3;
        border-radius: 12px;
        padding: 35px 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .login-card label {
        font-weight: bold;
        font-size: 16px;
        color: #222;
        display: block;
        margin-bottom: 6px;
    }

    .login-card input[type="text"],
    .login-card input[type="email"],
    .login-card input[type="password"],
    .login-card input[type="tel"] {
        width: 100%;
        background-color: #f2efe5;
        border: none;
        border-radius: 4px;
        padding: 9px 11px;
        margin-bottom: 18px; /* 👈 tăng khoảng cách giữa các dòng */
        font-size: 15px;
        outline: none;
    }

    .login-card input:focus {
        background-color: #f8f6ef;
        box-shadow: 0 0 0 2px #d82323 inset;
    }

    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 20px; /* 👈 khoảng cách dưới checkbox */
        gap: 8px;
    }

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

    /* Responsive */
    @media (max-width: 768px) {
        .login-card {
            padding: 25px;
        }
    }

</style>


    <!-- ====== TIÊU ĐỀ ====== -->
    <div class="section-title">ĐĂNG NHẬP</div>

    <!-- ====== FORM ====== -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                <div class="login-card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email">Tên đăng nhập:</label>
                            <input id="email" name="email" type="text"
                                   placeholder="Email hoặc số điện thoại"
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password">Mật khẩu:</label>
                            <input id="password" name="password" type="password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Ghi nhớ tôi</label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-danger px-5">Đăng nhập</button>
                        </div>

                        <p class="text-center mt-3 mb-0">
                            Chưa có tài khoản?
                            <a href="{{ route('register') }}" class="fw-bold link-dark">Đăng ký</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
