<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#fff9f0]">
        {{-- Logo --}}
        <div class="mb-6 text-center">
            <h1 class="text-4xl font-bold text-[#d32f2f]">
                <i class="fa-solid fa-film mr-2"></i> CINEMA
            </h1>
        </div>

        {{-- Form đăng nhập --}}
        <div class="w-full max-w-md bg-[#f6e4b6] rounded-lg shadow-lg p-6">
            <h2 class="text-center text-2xl font-bold mb-6 text-[#333]">ĐĂNG NHẬP</h2>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Tên đăng nhập --}}
                <div class="mb-4">
                    <label for="email" class="block font-semibold text-gray-700 mb-2">
                        Tên đăng nhập:
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-red-300">
                </div>

                {{-- Mật khẩu --}}
                <div class="mb-4">
                    <label for="password" class="block font-semibold text-gray-700 mb-2">
                        Mật khẩu:
                    </label>
                    <input id="password" type="password" name="password" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-red-300">
                </div>

                {{-- Ghi nhớ --}}
                <div class="flex items-center mb-4">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-red-500 focus:ring-red-300">
                    <label for="remember_me" class="ml-2 text-sm text-gray-700">
                        Ghi nhớ tôi
                    </label>
                </div>

                {{-- Nút đăng nhập --}}
                <div class="mb-4">
                    <button type="submit"
                        class="w-full bg-[#d32f2f] hover:bg-[#b71c1c] text-white font-semibold py-2 px-4 rounded-lg transition">
                        Đăng nhập
                    </button>
                </div>

                {{-- Liên kết đăng ký --}}
                <p class="text-center text-sm text-gray-700">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-red-600 font-semibold hover:underline">
                        Đăng ký
                    </a>
                </p>
            </form>
        </div>

        {{-- Footer --}}
        <div class="mt-10 text-center text-xs text-gray-500">
            <p>Chính Sách Khách Hàng | Trung Tuyên | Chính Sách Bảo Mật Thông Tin | Điều Khoản Sử Dụng</p>
        </div>
    </div>
</x-guest-layout>
