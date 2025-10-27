<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập admin
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Xử lý đăng nhập admin
     */
    public function login(Request $request)
    {
        // ✅ Kiểm tra dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // ✅ Dùng guard admin
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate(); // rất quan trọng – tránh lỗi 419
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng!',
        ])->onlyInput('email');
    }

    /**
     * Đăng xuất admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // ✅ Xóa session & token để tránh lỗi 419 khi đăng nhập lại
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
